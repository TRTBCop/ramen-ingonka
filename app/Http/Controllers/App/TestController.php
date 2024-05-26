<?php

namespace App\Http\Controllers\App;

use App\Models\Question;
use App\Services\QuestionService;
use App\Enums\StudentStatusEnum;
use App\Http\Controllers\BaseController;
use App\Http\Requests\App\TestRequest;
use App\Models\Student;
use App\Models\Test;
use App\Models\TestResult;
use App\Services\MathmlService;
use App\Services\TestService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Inertia\Inertia;
use Inertia\Response;

class TestController extends BaseController
{
    protected MathmlService $mathmlService;
    protected TestService $testService;

    public function __construct(MathmlService $mathmlService, TestService $testService)
    {
        $this->mathmlService = $mathmlService;
        $this->testService = $testService;
    }


    /**
     * 진단평가 목록
     * GET|HEAD | app/tests | app.tests.index
     * @return RedirectResponse|Response
     */
    public function index(): Response|RedirectResponse
    {
        /** @var Student $user */
        $user = auth()->user();

        /**
         * 무료체험이거나 서비스 이용중이고 진행중인 진단평가가 있다면 진단평가 상세로
         */
        if ($user->status == StudentStatusEnum::FREE || $user->status == StudentStatusEnum::IN_USE) {
            $testResult = TestResult::where([
                'student_id' => $user->id
            ])->whereNull('completed_at')->first();

            if ($testResult) {
                return to_route('app.tests.show', $testResult->test_id);
            }
        }

        // 출제중인 진단평가 목록 (학습결과 포함)
        // 무료 체험일 경우 해당 학년 학기만 진단평가 가능
        if ($user->isFree()) {
            $testId = config('dailykor.test.test_id_by_grade_term')[$user->grade][$user->term];
            $tests = Test::with([
                'result' => function ($query) use ($user) {
                    $query->where('student_id', $user->id)
                        ->whereNotNull('completed_at');
                }
            ])->where(['id' => $testId])->whereNotNull('published_at')->latest('id')->get();
        } else {
            $tests = Test::with([
                'result' => function ($query) use ($user) {
                    $query->where('student_id', $user->id)
                        ->whereNotNull('completed_at');
                }
            ])->whereNotNull('published_at')->latest('id')->get();
        }


        $testGroup = [];
        [$testGroup['lower'], $testGroup['upper']] = $tests->partition(function ($test) {
            return str_contains($test->title, '초등');
        });

        $testGroup['lower'] = $testGroup['lower']->values(); // 초등
        $testGroup['upper'] = $testGroup['upper']->values(); // 중고등

        return Inertia::render('tests/Index', [
            'test_group' => $testGroup,
            'config' => [
                'code' => [
                    'test' => config('dailykor.test'),
                ],
            ],
        ]);
    }

    /**
     *  진단평가 상세
     * GET|HEAD | app/tests/{test} | app.tests.show
     *
     * @param Test $test
     * @param Question|null $question
     * @return RedirectResponse|Response
     */
    public function show(Test $test, Question $question = null): Response|RedirectResponse
    {
        $routeName = request()->route()->getName();
        $isPreview = $routeName == 'app.tests.preview.show';

        if ($isPreview) {
            $questions = [$question];
            $testResult = [];
        } else {
            /** @var Student $user */
            $user = auth()->user();

            // 학생의 진단평가이력
            $testResults = TestResult::where([
                'student_id' => $user->id
            ])->get();


            // 이미 완료된 진단평가라면 메인으로
            $testResult = $testResults->where('test_id', $test->id)->first();
            if ($testResult && $testResult->completed_at) {
                return to_route('app.tests.index')->with('message', ['error', __('messages.app.tests.completed')]);
            }

            // 유저가 완료한 진단평가 갯수
            $testCompletedCount = $testResults->whereNotNull('completed_at')->count();

            // 응시할수있는 횟수를 초과 한경우
            if ($testCompletedCount >= $user->status->canTestCount()) {
                return to_route('app.tests.index')->with('message', ['error', __('messages.app.tests.ticket_purchase_required')]);
            }

            $extra = $testResult->extra ?? ['questions' => []];
            $questions = $this->testService->getBaiscQuestions($test);

            // 완료한 문제를 제외하고 제일 첫번째 문제를 세팅
            $question = $questions->whereNotIn('id', array_keys($extra['questions']))->first();

            // 확장 문제
            if (!$question) {
                $questions = $this->testService->getExtendQuestions($test, $extra);
                $question = $questions->whereNotIn('id', array_keys($extra['questions']))->first();
            }

            // 남은 퀴즈가 없는데 show 로 들어왔다면 500 (관리자 확인 필요)
            if (!$question) {
                Log::channel('tests')->error('남은퀴즈가 없습니다.', [
                    'student_id' => $user->id,
                    'test_result_id' => $testResult->id ?? 0,
                    'arr_question_id' => $questions->pluck('id')->toArray(),
                    'arr_completed_question_id' => []
                ]);
                // 진단평가 메인으로 돌렸을때 무한루프로 떨어짐
                abort(500, __('messages.error_occurred', ['comment' => 'ER-T001']));
            }
        }

        $isExtend = $this->testService->getIsExtend($test, $question);

        return Inertia::render('tests/Show', [
            'test' => $test,
            'timer' => isset($testResult->timer) ? $testResult->timer : 0,
            'questions' => $questions,
            'question' => QuestionService::questionToAppQuestion($question->toArray()),
            'is_extend' => $isExtend,
            'is_preview' => $isPreview,
            'config' => [
                'code' => [
                    'test' => config('dailykor.test'),
                ],
            ],
        ]);
    }

    /**
     *  진단평가 정답제출
     * GET|HEAD | app/tests/{test} | app.tests.store
     *
     * @param TestRequest $request
     * @param Test $test
     * @return RedirectResponse
     */
    public function store(TestRequest $request, Test $test): RedirectResponse
    {
        $questionId = request('question_id');
        $studentAnswers = request('answers');

        // 문제정보
        /** @var Question $question */
        $question = $test->questions()->with('curriculum')->whereNotNull('published_at')->findOrFail($questionId);

        // 학습이력
        /** @var TestResult $testResult */
        $testResult = $test->results()->firstOrNew([
            'student_id' => auth()->id()
        ], [
            'uuid' => Str::uuid()->toString(),
            'extra' => []
        ]);

        // 한문제를 풀어야 등록하기 때문에 등록일(테스트 시작일)을  1번문제 학습시간을 제외한 시간으로 넣어준다.
        if (!$testResult->created_at) {
            $testResult->created_at = now()->subSeconds($request->timer);
        }

        if (isset($request->timer)) {
            $testResult->timer = $request->timer;
        }

        $questionCount = 0;
        $correctCount = 0;
        $arrExtraAnswer = [];
        foreach ($question->answers as $answerKey => $contentAnswer) {
            // 정답체크
            $correct = $contentAnswer['answer'] == ($studentAnswers[$answerKey] ?? null);
            $arrExtraAnswer[] = [
                'correct' => $correct,
                'action' => $contentAnswer['action']
            ];
            $questionCount++;
            $correctCount += $correct;
        }

        $extra = $testResult->extra;
        $extra['questions'][$question->id] = [
                'id' => $question->id,
                'is_extend' => $this->testService->getIsExtend($test, $question),
                'element' => $question->curriculum->element->value,
                'question_count' => $questionCount, // 해설문제수
                'correct_count' => $correctCount, // 정답수
                'answers' => $arrExtraAnswer, // 결과
                'completed_at' => now(),
            ] + (request('meta_cognition') ? ['meta_cognition' => request('meta_cognition')] : []);
        $test->update(['extra' => $extra]);

        $question = $this->testService->getBaiscQuestions($test)->whereNotIn('id', array_keys($extra['questions']))->first();

        $completed_at = [];
        $point = [];
        $description = '답안제출';
        // 더이상 기본문제가 없다면 추가문제로직
        if (empty($question)) {
            $point['point'] = $this->testService->getPoint($extra);
            $extra += $point;

            // 기본문제 평가시간
            if (empty($extra['timer'])) {
                $extra['timer'] = $testResult->timer;
            }

            // 추가문제가 있는가?
            $question = $this->testService->getExtendQuestions($test, $extra)->whereNotIn('id', array_keys($extra['questions']))->first();

            if (!$question) {
                $description = '진단평가 완료';

                // 리포트 정보
                $extra['report'] = $this->testService->setReport($test, $extra, [
                    'test_title' => $test->title,
                    'name' => auth()->user()->name,
                ]);

                $completed_at['completed_at'] = now();
            }
        }

        $testResult->fill([
            'extra' => $extra,
            ...$point,
            ...$completed_at,
        ])->setActivitylogOptions([
            'description' => $description,
            'is_show' => 1,
        ])->save();


        if ($question) {
            // 미완료시 다음학습
            return to_route('app.tests.show', $test)->with('message', ['success', '정답등록성공']);
        }

        return to_route('app.tests.done', $test)->with('message', ['success', '진단평가 완료']);
    }

    /**
     * 완료
     *
     * @param Test $test
     * @return Response|RedirectResponse
     */
    public function done(Test $test): Response|RedirectResponse
    {
        /** @var Student $user */
        $user = auth()->user();

        $testResult = TestResult::where([
            'student_id' => $user->id,
            'test_id' => $test->id
        ])->whereNotNull('completed_at')->first();

        // 완료된 진단평가가 아니라면 403
        if (!$testResult) {
            return to_route('app.test.index')->with('message', ['error', __('messages.error_403')]);
        }

        $colQuestions = collect($testResult->extra['questions']);

        // 기본문제중 맞은문제
        $correctQuestions = $colQuestions->filter(function ($question) {
            return !$question['is_extend'] && $question['question_count'] == $question['correct_count'];
        });


        return Inertia::render('tests/Done', [
            'test' => $test,
            'uuid' => $testResult->uuid,
            'point' => $testResult->point,
            'level' => $testResult->extra['report']['level'] ?? 1,
            'group_question_count' => $colQuestions->where('is_extend', 0)->count(),
            'group_correct_count' => $correctQuestions->count(),
            'correct_count' => $colQuestions->sum('correct_count'),
            'question_count' => $colQuestions->sum('question_count'),
            'test_minute_second' => $testResult->extra['report']['test_minute_second'] ?? '', // 평가시간
            'completed_at' => $testResult->completed_at->format('Y-m-d H:i'),
            'config' => [
                'code' => [
                    'test' => config('dailykor.test'),
                ],
            ],
        ]);
    }

    /**
     * 학습 기록 소요시간 수정
     * PUT|PATCH | app/tests/{test}/timer | app.tests.timer.update
     * @param TestResult $testResult
     * @return \Illuminate\Http\JsonResponse
     */
    public function updateTimer(Test $test): JsonResponse
    {
        request()->validate([
            'timer' => 'required|strict_integer'
        ]);

        $timer = request('timer');

        // 학습이력
        /** @var TestResult $testResult */
        $testResult = $test->results()->firstOrNew([
            'student_id' => auth()->id()
        ], [
            'uuid' => Str::uuid()->toString(),
            'extra' => []
        ]);

        $testResult->update(['timer' => $timer]);

        return $this->sendResponse(['timer' => $timer], '성공');
    }

    /**
     * 리포트 상세
     * GET|HEAD | app/reports/test/{uuid} | app.tests.reports.show
     */
    public function showReport($uuid): View|\Illuminate\Foundation\Application|\Illuminate\Contracts\View\Factory|\Illuminate\Contracts\Foundation\Application
    {
        $testResult = TestResult::where('uuid', $uuid)->whereNotNull('completed_at')->firstOrFail();

        return view('pdf.report-test', [
            'test_result' => $testResult
        ]);

        /*
         * pdf 저장 출력 프로세스
        // todo result 에서 pdf 저장후 s3 업로드
        // showReport 에서는 pdf가 있는경우 pdf 출력 없는경우 view 출력
        */
        // $pdf = Browsershot::html(view('pdf.report-test', [
        //     'test_result' => $testResult
        // ])->render())->format('A4')->pdf();

        // return response()->make($pdf, 200, ['Content-Type' => 'application/pdf']);
    }
}
