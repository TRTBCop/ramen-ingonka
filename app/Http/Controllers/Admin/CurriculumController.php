<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\CurriculumRequest;
use App\Http\Resources\ListCollection;
use App\Models\Curriculum;
use App\Models\Question;
use App\Models\Training;
use App\Models\TrainingConceptText;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Inertia\Inertia;
use Inertia\Response;

class CurriculumController extends BaseController
{
    public function __construct(
        public string $name = '커리큘럼관리',
    )
    {
        $this->middleware(['permission:contents']);
    }

    /**
     * 커리큘럼 목록
     * GET|HEAD | admin/curricula | admin.curricula.index
     */
    public function index(): Response
    {
        $curricula = Curriculum::whereDescendantOf(1)->get()->toTree();

        $curriculumDepth1 = [];
        $curriculumDepth2 = [];
        foreach ($curricula as $curriculum) {
            foreach ($curriculum->children as $curriculum1) {
                $curriculumDepth1[$curriculum1->id] = $curriculum->name.'>'.$curriculum1->name;
                foreach ($curriculum1->children as $curriculum2) {
                    $curriculumDepth2[$curriculum1->id][$curriculum2->id] = $curriculum2->name;
                }
            }
        }


        $routeName = request()->route()->getName();

        $query = Curriculum::with(['trainings', 'ancestors' => function ($query) {
            $query->hasParent();
        }])->listFilter()->whereIsLeaf();
        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 10;
        $collection = $query->paginate($pageSize)
            ->appends([
                'filters' => [
                    'category_depth_1_id' => request()->filters['category_depth_1_id'] ?? null,
                    'category_depth_2_id' => request()->filters['category_depth_2_id'] ?? null,
                ],
                'filter_text' => request()->filter_text
            ]);

        return Inertia::render('curricula/Index', [
            'collection' => ListCollection::collection($collection),
            'config' => [
                'dbcode' => [
                    'curricula' => dbcode('curricula'),
                ],
            ],
            'curriculum_depth_1' => $curriculumDepth1,
            'curriculum_depth_2' => $curriculumDepth2,
            'route_name' => $routeName,
            'page' => [
                'active' => 'admin.curricula.index',
                'title' => $this->name.'목록',
                'breadcrumbs' => ['커리큘럼관리'],
            ],
        ]);
    }

    /**
     * 커리큘럼 계층구조
     * GET|HEAD | admin/curricula/nested-set | admin.curricula.nested-set
     */
    public function nestedSet(): Response
    {
        $curricula = Curriculum::defaultOrder()->get()->toTree();

        $nodes = [];
        $rootNodes = $curricula->pluck('id')->toArray();

        $traverse = function ($curricula, &$parent) use (&$traverse) {
            foreach ($curricula as $curriculum) {
                $node = ['id' => $curriculum->id, 'label' => $curriculum->name];
                if (count($curriculum->children)) {
                    $node['children'] = [];
                    $traverse($curriculum->children, $node['children']);
                }
                $parent[] = $node;
            }
        };

        $traverse($curricula, $nodes);

        return Inertia::render('curricula/NestedSet', [
            'nodes' => $nodes,
            'root_nodes' => $rootNodes,
            'route_name' => request()->route()->getName(),
            'page' => [
                'title' => $this->name.'목록',
                'breadcrumbs' => ['커리큘럼관리'],
            ],
        ]);
    }

    /**
     * 상세
     * GET|HEAD | admin/curricula/{curriculum} | admin.curricula.show
     *
     * @param Curriculum $curriculum
     * @return RedirectResponse|Response
     */
    public function show(Curriculum $curriculum): Response|RedirectResponse
    {
        $curriculum->load(['ancestors' => function ($query) {
            $query->hasParent();
        }]);

        return Inertia::render('curricula/Show', [
            'curriculum' => $curriculum,
            'config' => [
                'dbcode' => [
                    'curricula' => dbcode('curricula'),
                ],
            ],
            'page' => [
                'active' => 'admin.curricula.index',
                'title' => $this->name.'상세',
                'breadcrumbs' => ['커리큘럼관리'],
            ],
        ]);
    }

    /**
     * 개념훈련 지문생성
     * POST | admin/curricula/{curriculum}/1/texts | admin.curricula.texts.store
     *
     * @param Curriculum $curriculum
     * @return RedirectResponse
     */
    public function storeTrainingConceptTexts(Curriculum $curriculum): RedirectResponse
    {
        /**
         * @var Training $training
         */
        $training = $curriculum->trainings()->where('stage', 1)->first();
        $trainingConceptText = $training->training_concept_texts()->create();

        return to_route('admin.curricula.training1.texts.show', [$curriculum->id, $trainingConceptText->id, 'readings'])->with('message', ['success', '저장성공']);
    }

    /**
     * 개념훈련 지문삭제
     * DELETE | admin/curricula/{curriculum}/1/texts/{trainingConceptTexts} | admin.curricula.texts.destroy
     *
     * @param Curriculum $curriculum
     * @param TrainingConceptText $trainingConceptText
     * @return RedirectResponse
     */
    public function destroyTrainingConceptTexts(Curriculum $curriculum, TrainingConceptText $trainingConceptText): RedirectResponse
    {
        /**
         * @var Training $training
         */
        $training = $curriculum->trainings()->withCount('training_concept_texts')->where('stage', 1)->first();

        if ($training->training_concept_texts_count <= 1) {
            return redirect()->back()->with('message', ['error', '최소 한 개의 지문은 있어야 합니다.']);
        }


        if ($training->id == $trainingConceptText->training_id) {
            // 지문에 연결된 문제를 제거한다

            // 연관문제 조회
            $questions = $training->questions()->withPivot('extra')->get()?->filter(function (Question $question) use ($trainingConceptText) {
                return isset($question->pivot->extra['type'])
                && isset($question->pivot->extra['model'])
                && $question->pivot->extra['model'] == TrainingConceptText::class
                && $question->pivot->extra['model_id'] == $trainingConceptText->id ?? 0;
            });

            $questions->each->delete();
            $trainingConceptText->delete(); // hard
        }

        return to_route('admin.curricula.trainings.show', [$curriculum->id, 1])->with('message', ['success', '저장성공']);
    }

    /**
     * 상세
     * GET|HEAD | admin/curricula/{curriculum}/{trainingStage} | admin.curricula.training.show
     *
     * @param Curriculum $curriculum
     * @param int $trainingStage
     * @return RedirectResponse|Response
     */
    public function showTraining(Curriculum $curriculum, int $trainingStage = 1): Response|RedirectResponse
    {
        $curriculum->load(['ancestors' => function ($query) {
            $query->hasParent();
        }]);

        // 트레이닝
        $trainingMethod = 'showTraining'.$trainingStage;
        if (method_exists($this, $trainingMethod)) {
            return call_user_func([$this, $trainingMethod], $curriculum);
        }

        abort(404);
    }

    /**
     * 개념훈련- 지문세트 (개념읽기 | 개념요약 | 개념다지기)
     *
     * GET|HEAD | admin/curricula/{curriculum}/1/texts/{trainingConceptText}/{type} | admin.curricula.training1.text.show
     *
     *
     * @param Curriculum $curriculum
     * @param TrainingConceptText |null $trainingConceptText
     * @param string|null $type
     * @return RedirectResponse|Response
     */
    public function showTraining1(Curriculum $curriculum, TrainingConceptText|null $trainingConceptText = null, string|null $type = 'readings'): Response|RedirectResponse
    {
        /**
         * @var Training|null $training
         */
        $training = $curriculum->trainings()->firstOrCreate([
            'stage' => 1
        ]);

        // 지문 번호모음
        $trainingConceptTextIds = $training->training_concept_texts()->get()->pluck('id')->toArray();
        // 지문이 없다면 지문 한개 생성
        if (!$trainingConceptTextIds) {
            $trainingConceptTextIds[] = $training->training_concept_texts()->create()->id;
        }

        // 잘못된 요청
        if ($trainingConceptText && $trainingConceptText->training_id != $training->id) {
            return redirect()->back()->with('message', ['error', __('messages.invalid_request', ['comment' => ''])]);
        }

        // 선택된 지문이 없다면 첫지문을 가저옴
        if (!$trainingConceptText) {
            $trainingConceptText = $training->training_concept_texts()->find($trainingConceptTextIds[0]);
        }

        if ($type == 'reinforcements') { // 개념다지기
            $title = '개념다지기';
            $view = 'ShowTraining1TextReinforcement';
        } elseif ($type == 'summarizations') {
            $title = '개념요약';
            $view = 'ShowTraining1TextSummarization';
        } else {
            $type = 'readings';
            $title = '개념읽기';
            $view = 'ShowTraining1TextReading';
        }

        $contents = $trainingConceptText->{$type};

        // 연관문제 조회
        $questions = array_values($training->questions()->withPivot('extra')->get()?->filter(function (Question $question) use ($type, $trainingConceptText) {
            return isset($question->pivot->extra['type'])
            && isset($question->pivot->extra['model'])
            && $question->pivot->extra['type'] == 'training_concept_texts.'.$type
            && $question->pivot->extra['model'] == TrainingConceptText::class
            && $question->pivot->extra['model_id'] == $trainingConceptText->id ?? 0;
        })->toArray());

        return Inertia::render('curricula/'.$view, [
            'curriculum' => $curriculum,
            'training' => $training,
            'contents' => $contents,
            'questions' => $questions,
            'question_extra' => [
                'type' => 'training_concept_texts.'.$type,
                'model' => TrainingConceptText::class,
                'model_id' => $trainingConceptText->id
            ],
            'training_concept_text_id' => $trainingConceptText->id, // 지문 정보
            'training_concept_text_type' => $type, // 지문의 타입 정보
            'training_concept_text_ids' => $trainingConceptTextIds ?? [], // 지문 아이디
            'page' => [
                'active' => 'admin.curricula.index',
                'title' => $this->name.'상세-개념훈련-지문-'.$title,
                'breadcrumbs' => ['커리큘럼관리'],
            ],
        ]);
    }

    /**
     * GET|HEAD | admin/curricula/{curriculum}/1/operations | admin.curricula.training1.operations.show
     * 개념훈련 - 기초연산
     * @param Curriculum $curriculum
     * @return RedirectResponse|Response
     */
    public function showTraining1Operations(Curriculum $curriculum): Response|RedirectResponse
    {
        $training = $curriculum->trainings()->where('stage', 1)->first();
        if (!$training) {
            // 트레이닝 정보가 없다면 개념훈련 첫페이지로
            return to_route('admin.curricula.trainings.show', [$curriculum->id, 1]);
        }

        $trainingConceptTextIds = $training->training_concept_texts()->get()->pluck('id')->toArray();
        // 연관문제 조회
        $questions = array_values($training->questions()->withPivot('extra')->get()?->filter(function (Question $question) {
            return isset($question->pivot->extra['type'])
                && $question->pivot->extra['type'] == 'operations';
        })->toArray());

        return Inertia::render('curricula/ShowTraining1Operations', [
            'curriculum' => $curriculum,
            'training_concept_text_ids' => $trainingConceptTextIds ?? [], // 지문 아이디
            'contents' => $training->contents['basic_operations'] ?? ['questions' => []],
            'training' => $training,
            'questions' => $questions,
            'question_extra' => [
                'type' => 'operations',
            ],
            'page' => [
                'active' => 'admin.curricula.index',
                'title' => $this->name.'상세-개념훈련-기초연산',
                'breadcrumbs' => ['커리큘럼관리'],
            ],
        ]);
    }

    /**
     * 개념훈련-기초연산 저장
     *
     * @param Curriculum $curriculum
     * @return RedirectResponse
     */
    public function updateTraining1Operations(Curriculum $curriculum): RedirectResponse
    {
        $training = $curriculum->trainings()->where('stage', 1)->first();

        request()->validate([
            'basic_operations' => 'present|array',
            'basic_operations.questions' => 'present|array',
            'basic_operations.questions.*.id' => 'strict_integer',
        ]);

        $contents = [
            'basic_operations' => request('basic_operations', [])
        ];
        $training->contents = $contents;

        // 검수여부
        if (isset(request()->is_published) && request('is_published')) {
            $training->published_at = now();
        } elseif (isset(request()->is_published)) {
            $training->published_at = null;
        }

        if ($training->isDirty()) {
            $training->setActivitylogOptions([
                'description' => '개념훈련-기초연산수정',
                'is_show' => 1,
            ])->save();
        }

        return to_route('admin.curricula.training1.operations.show', [$curriculum->id])->with('message', ['success', '저장성공']);
    }

    /**
     * @param Curriculum $curriculum
     * @return Response
     */
    private function showTraining2(Curriculum $curriculum): Response
    {
        /**
         * @var Training|null $training
         */
        $training = $curriculum->trainings()->firstOrCreate([
            'stage' => 2
        ]);

        // 연관문제 조회
        $questions = $training->questions()->withPivot('extra')->get();

        return Inertia::render('curricula/ShowTraining2', [
            'curriculum' => $curriculum,
            'training' => $training,
            'questions' => $questions,
            'question_extra' => [
                'type' => 'trainings.stage_2',
            ],
            'page' => [
                'active' => 'admin.curricula.index',
                'title' => $this->name.'상세-유형훈련',
                'breadcrumbs' => ['커리큘럼관리'],
            ],
        ]);
    }


    /**
     * @param Curriculum $curriculum
     * @return Response
     */
    private function showTraining3(Curriculum $curriculum): Response
    {
        /**
         * @var Training|null $training
         */
        $training = $curriculum->trainings()->firstOrCreate([
            'stage' => 3
        ]);

        // 연관문제 조회
        $questions = $training->questions()->withPivot('extra')->get();

        return Inertia::render('curricula/ShowTraining3', [
            'curriculum' => $curriculum,
            'training' => $training,
            'questions' => $questions,
            'question_extra' => [
                'type' => 'trainings.stage_3',
            ],
            'page' => [
                'active' => 'admin.curricula.index',
                'title' => $this->name.'상세-서술형훈련',
                'breadcrumbs' => ['커리큘럼관리'],
            ],
        ]);
    }

    /**
     * 등록
     * POST | admin/curricula | admin.curricula.store
     *
     * @param CurriculumRequest $request
     * @return JsonResponse
     */
    public function store(CurriculumRequest $request): JsonResponse
    {
        $curriculum = new Curriculum();
        return parent::setStore($this, $curriculum, $request);
    }

    /**
     * 수정
     *
     * PUT|PATCH | admin/curricula/{curriculum} | admin.curricula.update
     * @param CurriculumRequest $request
     * @param Curriculum $curriculum
     * @return JsonResponse
     */
    public function update(CurriculumRequest $request, Curriculum $curriculum): JsonResponse
    {
        try {
            $messageType = $request->isMethod('POST') ? '등록' : '수정';


            $input = $request->all();
            $changeParentId = $curriculum->parent_id != request('parent_id');


            $curriculum->fill($input)->setActivitylogOptions([
                'description' => '커리큘럼 '.$messageType,
                'is_show' => 1,
            ]);
            $curriculum->parent_id = request('parent_id');
            $curriculum->save();


            if (isset($request->position)) {
                $oldPosition = 0;
                if ($changeParentId) {
                    $oldPosition = $curriculum->getSiblings()->count(); // total node
                }
                $changePosition = request('old_position', $oldPosition) - request('position');

                if ($changePosition > 0) {
                    $curriculum->up($changePosition);
                } else {
                    $curriculum->down(abs($changePosition));
                }
            }

            return $this->sendResponse(['curriculum' => $curriculum], $messageType.' 성공');
        } catch (Exception $e) {
            return $this->sendError($messageType.' 실패', $e->getMessage(), $e->getCode());
        }
    }

    /**
     * 훈련저장
     * PUT|PATCH | admin/curricula/{curriculum}/{trainingStage} | admin.curricula.training.update
     *
     * @param Curriculum $curriculum
     * @param $trainingStage
     * @return RedirectResponse
     */
    public function updateTraining(Curriculum $curriculum, $trainingStage): RedirectResponse
    {
        $trainingMethod = 'updateTraining'.$trainingStage;
        if (method_exists($this, $trainingMethod)) {
            return call_user_func([$this, $trainingMethod], $curriculum);
        }

        return redirect()->back()->with('message', ['error', __('messages.error_occurred', ['comment' => ''])]);
    }


    /**
     * 개념훈련
     *
     * PUT|PATCH | admin/curricula/{curriculum}/{trainingStage} | admin.curricula.training.update
     * PUT|PATCH | admin/curricula/{curriculum}/1/texts/{trainingConceptText}/{type} | admin.curricula.training1.texts.update
     *
     * @param Curriculum $curriculum
     * @param TrainingConceptText|null $trainingConceptText
     * @param string|null $type
     * @return RedirectResponse
     */
    public function updateTraining1(Curriculum $curriculum, TrainingConceptText|null $trainingConceptText = null, string|null $type = 'readings'): RedirectResponse
    {
        $stage = 1;

        /**
         * @var Training|null $training
         */
        $training = $curriculum->trainings()->where('stage', $stage)->first();
        if (empty($training)) {
            return redirect()->back()->with('message', ['error', __('messages.invalid_request', ['comment' => 'UT1001'])]);
        }

        if ($trainingConceptText && $trainingConceptText->training_id != $training->id) {
            return redirect()->back()->with('message', ['error', __('messages.invalid_request', ['comment' => 'UT1002'])]);
        }

        // 개념읽기
        if ($trainingConceptText && $type == 'readings') {
            $validator = Validator::make(request()->all(), [
                'readings' => 'present|array',
                'readings.*.text' => 'required|string',
                'readings.*.type' => 'required|strict_integer|in:0,1,2',

                // 이미지 형인경우
                'readings.*.image' => 'required_if:readings.*.type,1|array',
                'readings.*.image.src' => 'required_if:readings.*.type,1|string',
                'readings.*.image.last' => 'required_if:readings.*.type,1|bool',

                // 정답고르기인경우 무조건 있어야 함
                'readings.*.question' => 'required_if:readings.*.type,2|array',
                'readings.*.question.id' => 'required_if:readings.*.type,2|strict_integer|exists:questions,id',
            ]);


            try {
                if ($validator->fails()) {
                    throw (new ValidationException($validator));
                }
                $input = $validator->validated();
            } catch (ValidationException $e) {
                $errors = $validator->errors();

                // 커스텀 에러 메시지를 생성합니다.
                $customMessages = [];
                foreach ($errors->getMessages() as $field => $message) {
                    // 문제형 미등록문제
                    if (preg_match('/readings\.(\d+)\.question/', $field, $matches)) {
                        $customMessages[$field] = ($matches[1] + 1).'번이 `문제형` 타입이나 등록된 문제가없습니다.';
                        continue;
                    }

                    // 이미지형 미등록이미지
                    if (preg_match('/readings\.(\d+)\.image/', $field, $matches)) {
                        $customMessages[$field] = ($matches[1] + 1).'번이 `이미지` 타입이나 등록된 이미지가 없습니다.';
                        continue;
                    }

                    $customMessages[$field] = $message;
                }

                return redirect()->back()->withErrors($customMessages)->with('message', ['error', '문제가 발생했습니다.']);
            }


            // 임시 이미지 업로드
            $readings = fileTempMove($input['readings'], $trainingConceptText->readings ?? [], 'curricula/'.$curriculum->id.'/'.$stage.'/texts/'.$trainingConceptText->id);


            $trainingConceptText->readings = $readings;

            // 개념요약
        } elseif ($trainingConceptText && $type == 'summarizations') {
            $validator = Validator::make(request()->all(), [
                'summarizations.questions' => 'present|array',
                'summarizations.questions.*.id' => 'strict_integer|exists:questions,id',
            ]);

            try {
                if ($validator->fails()) {
                    throw (new ValidationException($validator));
                }
                $input = $validator->validated();
            } catch (ValidationException $e) {
                return redirect()->back()->withErrors($validator)->with('message', ['error', '문제가 발생했습니다.']);
            }

            // 임시 이미지 업로드
            $summarizations = fileTempMove($input['summarizations'], $trainingConceptText->reading ?? [], 'curricula/'.$curriculum->id.'/'.$stage.'/texts/'.$trainingConceptText->id);

            $trainingConceptText->summarizations = $summarizations;

            // 개념다지기
        } elseif ($trainingConceptText && $type == 'reinforcements') {
            $validator = Validator::make(request()->all(), [
                'reinforcements.questions' => 'present|array',
                'reinforcements.questions.*.id' => 'strict_integer|exists:questions,id',
            ]);

            try {
                if ($validator->fails()) {
                    throw (new ValidationException($validator));
                }
                $input = $validator->validated();
            } catch (ValidationException $e) {
                return redirect()->back()->withErrors($validator)->with('message', ['error', '문제가 발생했습니다.']);
            }

            // 임시 이미지 업로드
            $reinforcements = fileTempMove($input['reinforcements'], $trainingConceptText->reading ?? [], 'curricula/'.$curriculum->id.'/'.$stage.'/texts/'.$trainingConceptText->id);

            $trainingConceptText->reinforcements = $reinforcements;
        }

        $trainingConceptText?->setActivitylogOptions([
            'description' => '개념훈련-지문-'.$type.'저장',
            'is_show' => 1,
        ])->save();

        // 검수여부
        if (isset(request()->is_published) && request('is_published')) {
            $training->published_at = now();
        } elseif (isset(request()->is_published)) {
            $training->published_at = null;
        }

        if ($training->isDirty()) {
            $training->setActivitylogOptions([
                'description' => '개념훈련 저장',
                'is_show' => 1,
            ])->save();
        }

        if ($trainingConceptText && $type) {
            return to_route('admin.curricula.training1.texts.show', [$curriculum->id, $trainingConceptText, $type])->with('message', ['success', '저장성공']);
        } else {
            return to_route('admin.curricula.show', [$curriculum->id, $stage])->with('message', ['success', '저장성공']);
        }
    }


    /**
     * @param Curriculum $curriculum
     * @return RedirectResponse
     */
    private function updateTraining2(Curriculum $curriculum): RedirectResponse
    {
        $stage = 2;

        /**
         * @var Training|null $training
         */
        $training = $curriculum->trainings()->where('stage', $stage)->first();
        if (empty($training)) {
            return redirect()->back()->with('message', ['error', __('messages.invalid_request', ['comment' => 'UT2001'])]);
        }

        request()->validate([
            'contents' => 'array|size:4',
            'contents.*.questions.*.id' => 'strict_integer|exists:questions,id',
        ], [], [
            'contents.*.questions.*.id' => '문제번호',
        ]);

        // 검수여부
        if (isset(request()->is_published) && request('is_published')) {
            $training->published_at = now();
        } elseif (isset(request()->is_published)) {
            $training->published_at = null;
        }

        $training->fill(request()->input())->setActivitylogOptions([
            'description' => '유형훈련 저장',
            'is_show' => 1,
        ])->save();

        return to_route('admin.curricula.trainings.show', [$curriculum->id, $stage])->with('message', ['success', '저장성공']);
    }

    /**
     * @param Curriculum $curriculum
     * @return RedirectResponse
     */
    private function updateTraining3(Curriculum $curriculum): RedirectResponse
    {
        $stage = 3;

        /**
         * @var Training|null $training
         */
        $training = $curriculum->trainings()->where('stage', $stage)->first();
        if (empty($training)) {
            return redirect()->back()->with('message', ['error', __('messages.invalid_request', ['comment' => 'UT3001'])]);
        }
        request()->validate([
            'contents' => 'array|size:3',
            'contents.*.questions.*.id' => 'strict_integer|exists:questions,id',
        ], [], [
            'contents.*.questions.*.id' => '문제번호',
        ]);

        // 검수여부
        if (isset(request()->is_published) && request('is_published')) {
            $training->published_at = now();
        } elseif (isset(request()->is_published)) {
            $training->published_at = null;
        }

        $training->fill(request()->input())->setActivitylogOptions([
            'description' => '서술형훈련 저장',
            'is_show' => 1,
        ])->save();

        return to_route('admin.curricula.trainings.show', [$curriculum->id, $stage])->with('message', ['success', '저장성공']);
    }

    /**
     * 삭제
     * DELETE | admin/curricula/{curriculum} | admin.curricula.destroy
     * @param Curriculum $curriculum
     * @return JsonResponse
     */
    public function destroy(Curriculum $curriculum): JsonResponse
    {
        try {
            if ($curriculum->descendants->count()) { // 자식노드있으면 삭제 불가
                throw new Exception(__('messages.admin.curricula.child_exists_cannot_delete'));
            }

            if ($curriculum->trainings()->count()) { // 트레이닝 있으면 삭제 불가
                throw new Exception(__('messages.admin.curricula.training_exists_cannot_delete'));
            }

            $curriculum->delete();
            return $this->sendResponse([], '삭제 성공');
        } catch (Exception $e) {
            return $this->sendError('삭제 실패', $e->getMessage(), $e->getCode());
        }
    }
}
