<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\QuestionRequest;
use App\Http\Resources\ListCollection;
use App\Models\Pivots\QuestionPivot;
use App\Models\Question;
use App\Services\CurriculumService;
use App\Services\QuestionService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\Tags\Tag;

class QuestionController extends BaseController
{
    public function __construct(
        private readonly CurriculumService $curriculumService,
        private readonly QuestionService   $questionService,
        public string                      $name = '문제관리',
    ) {
        $this->middleware(['permission:contents']);
    }

    /**
     * 목록
     *
     * GET|HEAD | admin/questions | admin.questions.index
     * @return JsonResponse|Response
     */
    public function index(): JsonResponse|Response
    {
        $routeName = request()->route()->getName();
        $query = Question::with(['curriculum.ancestors:id,name,parent_id'])->listFilter();
        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 10;


        $collection = ListCollection::collection(
            $query->paginate($pageSize)->appends([
                'filters' => [
                    'type' => request()->filters['b2c'] ?? null,
                    'tags' => request()->filters['status'] ?? null,
                ],
                'filter_text' => request()->filter_text
            ])
        );

        if (!request()->wantsJson() || request()->inertia()) {
            return Inertia::render('questions/Index', [
                'collection' => $collection,
                'curriculum_id_to_name' => $this->curriculumService->getCurriculumIdToName(),
                'config' => [
                    'dbcode' => [
                        'questions' => dbcode('questions'),
                    ],
                ],
                'route_name' => $routeName,
                'page' => [
                    'title' => '문제목록',
                    'breadcrumbs' => ['문제관리'],
                ],
            ]);
        } else {
            return $this->sendResponse([
                'collection' => $collection->response()->getData(),
            ], '목록조회 성공');
        }
    }


    /**
     * 등록
     *
     * POST | admin/questions | admin.questions.store
     */
    public function store(QuestionRequest $request): JsonResponse|RedirectResponse
    {
        $question = new Question();
        return parent::setStore($this, $question, $request);
    }


    /**
     * 상세
     *
     * GET|HEAD | admin/questions/{academy} | admin.questions.show
     */
    public function show(Question $question): JsonResponse
    {
        $question->load([
            'trainings',
            'tests',
            'curriculum',
            'tags'
        ]);


        return $this->sendResponse([
            'question' => $question,
            'curriculum_id_to_name' => $this->curriculumService->getCurriculumIdToName(),
            'config' => [
                'dbcode' => [
                    'questions' => dbcode('questions'),
                ]
            ],
            'page' => [
                'active' => 'admin.questions.index',
                'title' => '문제상세',
                'breadcrumbs' => ['문제관리'],
            ],
        ], '문제상세 조회 성공');
    }


    /**
     * 수정
     *
     * PUT|PATCH | admin/questions/{question} | admin.questions.update
     */
    public function update(QuestionRequest $request, Question $question): JsonResponse
    {
        $messageType = $request->isMethod('POST') ? '등록' : '수정';

        $input = $request->all();
        $question = $this->questionService->update($question, $input);

        return $this->sendResponse(['question' => $question], $messageType.'성공');
    }

    /**
     * 삭제 (soft)
     *
     * DELETE | admin/questions/{academy} | admin.questions.destroy
     */
    public function destroy(Question $question): JsonResponse|RedirectResponse
    {
        $message = $question->title.'문제삭제';
        $question->setActivityLogOptions([
            'description' => $message,
            'is_show' => 1,
        ])->delete();

        if (!request()->wantsJson() || request()->inertia()) {
            return to_route('admin.questions.index')->with('message', ['success', $message.'성공']);
        } else {
            return $this->sendResponse([], $message.' 성공');
        }
    }
}
