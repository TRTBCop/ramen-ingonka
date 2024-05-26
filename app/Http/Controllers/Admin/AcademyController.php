<?php

namespace App\Http\Controllers\Admin;

use App\Enums\StudentStatusEnum;
use App\Exports\AcademyExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\AcademyRequest;
use App\Http\Resources\ListCollection;
use App\Models\Academy;
use App\Models\Student;
use App\Services\AcademyService;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Maatwebsite\Excel\Facades\Excel;
use Spatie\Tags\Tag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AcademyController extends BaseController
{
    public function __construct(
        public string $name = '학원',
    ) {
        $this->middleware(['permission:academy']);
    }

    /**
     * 학원목록
     *
     * GET|HEAD | admin/academies | admin.academies.index
     * @return Response
     */
    public function index(): Response
    {
        $routeName = request()->route()->getName();
        $query = Academy::listFilter()->withCount(['students', 'activeStudents']);

        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 10;
        $collection = $query->paginate($pageSize)
            ->appends([
                'filters' => [
                    'tags' => request()->filters['b2c'] ?? null,
                    'status' => request()->filters['status'] ?? null,
                ],
                'filter_text' => request()->filter_text
            ]);

        return Inertia::render('academy/Index', [
            'collection' => ListCollection::collection($collection),
            'tags' => Tag::where(['type' => 'admin.academies'])->get(), // 전체 태그목록
            'config' => [
                'dbcode' => [
                    'academies' => config('dailykor.dbcode.academies'),
                ],
            ],
            'route_name' => $routeName,
            'page' => [
                'title' => '학원목록',
                'breadcrumbs' => ['학원관리'],
            ],
        ]);
    }

    /**
     * 등록 Form
     *
     * GET|HEAD | admin/academies/create | admin.academies.create
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('academy/Create', [
            'config' => [
                'dbcode' => [
                    'academies' => config('dailykor.dbcode.academies'),
                ],
                'service' => [
                    'academy' => [
                        'basic_price' => config('dailykor.service.academy.basic_price'),
                    ],
                ],
            ],
            'page' => [
                'active' => 'admin.academies.index',
                'title' => '학원생성',
                'breadcrumbs' => ['학원관리'],
            ],
        ]);
    }

    /**
     * 액셀다운로드
     *
     * GET|HEAD | admin/academies-export | admin.academies.export
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new AcademyExport(), '학원목록 '.now()->format('Y-m-d').'.xlsx');
    }


    /**
     * 등록
     *
     * POST | admin/academies | admin.academies.store
     */
    public function store(AcademyRequest $request): RedirectResponse
    {
        $academy = new Academy();
        parent::setStore($this, $academy, $request);

        /*
        // 원장 선생님 등록
        $teacher = new Teacher();
        $teacher->fill([
            'academy_id' => $academy->id,
            'access_id' => $request->access_id,
            'password' => $request->password,
            'name' => $request->owner_name ?: $request->name.'원장선생님',
        ])->setActivitylogOptions([
            'description' => '원장 선생님이 등록되었습니다.',
            'is_show' => 1,
        ])->save();
        $teacher->assignRole('owner');
        */

        $academy->refresh();
        return to_route('admin.academies.show', $academy->id)->with('message', ['success', '저장성공']);
    }


    /**
     * 상세
     *
     * GET|HEAD | admin/academies/{academy} | admin.academies.show
     */
    public function show(Academy $academy): Response
    {
        $academy->load([
            'media', 'tags',
            'payments' => function ($query) {
                $query->take(5);
            },
        ])->loadCount([
            'students' => function ($query) {
                $query->where('status', StudentStatusEnum::IN_USE); // 이용중학생수
            },
        ])->append(['logo', 'files']);

        $tags = [];
        foreach ($academy->tags as $tag) {
            $tags[] = $tag->name;
        }

        return Inertia::render('academy/Show', [
            'academy' => $academy,
            'tags' => $tags,
            'config' => [
                'dbcode' => [
                    'academies' => config('dailykor.dbcode.academies'),
                ],
                'service' => [
                    'academy' => [
                        'basic_price' => config('dailykor.service.academy.basic_price'),
                    ],
                ],
            ],
            'page' => [
                'active' => 'admin.academies.index',
                'title' => '학원상세',
                'breadcrumbs' => ['학원관리'],
            ],
        ]);
    }


    /**
     * 수정
     *
     * PUT|PATCH | admin/academies/{academy} | admin.academies.update
     */
    public function update(AcademyRequest $request, Academy $academy): RedirectResponse
    {
        $messageType = $request->isMethod('POST') ? '등록' : '수정';

        $input = $request->all();
        $input['manager_memo'] = $input['manager_memo'] ?? '';

        $academy->fill($input)->setActivitylogOptions([
            'description' => $this->name.'가 '.$messageType.'되었습니다.',
            'is_show' => 1,
        ])->save();

        // tags
        if (isset($input['tags'])) {
            if (!$input['tags']) {
                $academy->tags()->detach();
            } else {
                $academy->syncTagsWithType($input['tags'], 'admin.academies');
            }
        }

        $academyService = new AcademyService($academy);

        if (request()->remove_logo) {
            $academyService->logoRemove();
        }

        $academyService->logoUpload();

        $academy->refresh();
        return to_route('admin.academies.show', $academy->id)->with('message', ['success', '저장성공']);
    }

    /**
     * 삭제 (soft)
     *
     * DELETE | admin/academies/{academy} | admin.academies.destroy
     */
    public function destroy(Academy $academy): RedirectResponse
    {
        $message = $academy->name.'학원삭제';
        $academy->setActivityLogOptions([
            'description' => $message,
            'is_show' => 1,
        ])->delete();

        return to_route('admin.academies.index')->with('message', ['success', $message.'성공']);
    }

    /**
     * 본사 > 학원상세 > 학생 목록
     *
     * GET|HEAD | admin/academies/{academies}/student-list | admin.academies.studentList
     * @return void
     */
    public function studentList(Academy $academy): Response
    {
        $query = Student::where('academy_id', $academy->id)->with('academy')->listFilter();
        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 50;
        $collection = $query->paginate($pageSize);

        return Inertia::render('academy/Students', [
            'collection' => ListCollection::collection($collection),
            'academy' => $academy,
            'route_name' => request()->route()->getName(),
            'config' => [
                'dbcode' => [
                    'students' => config('dailykor.dbcode.students'),
                    'academies' => config('dailykor.dbcode.academies'),
                ],
            ],
            'page' => [
                'active' => 'admin.academies.students',
                'title' => '학생목록',
                'breadcrumbs' => ['학원상세'],
            ],
        ]);
    }
}
