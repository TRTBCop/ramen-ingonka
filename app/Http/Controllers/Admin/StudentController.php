<?php

namespace App\Http\Controllers\Admin;

use App\Exports\StudentExport;
use App\Http\Controllers\BaseController;
use App\Http\Requests\StudentRequest;
use App\Http\Resources\ListCollection;
use App\Models\Academy;
use App\Models\Admin;
use App\Models\Student;
use App\Models\TrainingResult;
use App\Services\StudentService;
use Exception;
use Illuminate\Http\RedirectResponse;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Http\JsonResponse;
use Inertia\Inertia;
use Inertia\Response;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Spatie\Activitylog\Models\Activity;

class StudentController extends BaseController
{
    private StudentService $studentService;

    public function __construct(
        StudentService $studentService,
        public string  $name = '학생'
    ) {
        $this->middleware(['permission:manager']);
        $this->studentService = $studentService;
    }

    /**
     * 학생목록
     *
     * GET|HEAD | admin/students | admin.students.index
     * GET|HEAD | admin/academies/{academy}/students | admin.academies.students.index
     *
     * @param Academy|null $academy
     * @return Response
     */
    public function index(Academy $academy = null): Response
    {
        $routeName = request()->route()->getName();
        $academies = Academy::all()->pluck('name', 'id');
        $extra = [];
        $component = 'student/Index';

        $active = 'admin.students.index';

        $query = Student::listFilter($academy);

        if (request()->show_all) {
            $collection = $query->get();
        } else {
            $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 50;
            $collection = $query->paginate($pageSize)->appends([
                'filters' => [
                    'b2c' => request()->filters['b2c'] ?? null,
                    'status' => request()->filters['status'] ?? null,
                    'academy_id' => request()->filters['academy_id'] ?? null
                ],
                'filter_text' => request()->filter_text
            ]);
        }
        $collection->append(['txt_type', 'txt_status']);


        return Inertia::render($component, array_merge([
            'collection' => ListCollection::collection($collection),
            'academies' => $academies,
            'route_name' => $routeName,
            'config' => [
                'dbcode' => [
                    'students' => dbcode('students'),
                ],
            ],
            'page' => [
                'active' => $active,
                'title' => '학생목록',
                'breadcrumbs' => ['학생관리'],
            ],
        ], $extra));
    }

    /**
     * 액셀다운로드
     *
     * GET|HEAD | admin/students-export | admin.students.export
     *
     * @throws \PhpOffice\PhpSpreadsheet\Exception
     * @throws \PhpOffice\PhpSpreadsheet\Writer\Exception
     */
    public function export(): BinaryFileResponse
    {
        return Excel::download(new StudentExport(), '학생목록 '.now()->format('Y-m-d').'.xlsx');
    }

    /**
     * 등록
     *
     * POST | admin/students  | admin.students.store
     */
    public function store(StudentRequest $request, Academy $academy): RedirectResponse
    {
        $message = $this->name.' 등록';

        try {
            $input = $request->all();
            $input['academy_id'] = $academy->id;

            if (isset($input['password']) && $input['password']) {
                $input['password'] = bcrypt($input['password']);
            } else {
                unset($input['password']);
            }

            $student = new Student();
            $student->fill($input)->setActivitylogOptions([
                'description' => $message,
                'is_show' => 1,
            ])->save();

            return to_route('admin.students.show', $student)->with('message', ['success', $message.'성공']);
        } catch (Exception $e) {
            return redirect()->back()->with('message', ['error', $e->getMessage()]);
        }
    }


    /**
     * 상세
     *
     * GET|HEAD | admin/students/{student} | admin.students.show
     */
    public function show(Student $student): Response
    {
        $student->load(['academy'])->append(['avatar'])->makeVisible('manager_memo');

        return Inertia::render('student/Show', [
            'academies' => Academy::all()->pluck('name', 'id'),
            'student' => $student,
            'config' => [
                'dbcode' => [
                    'students' => config('dailykor.dbcode.students'),
                ],
            ],
            'page' => [
                'active' => 'admin.students.index',
                'title' => '학생상세',
                'breadcrumbs' => ['학생관리'],
            ],
        ]);
    }

    /**
     * 수정
     *
     * PUT|PATCH | admin/students/{student} | admin.students.update
     */
    public function update(StudentRequest $request, Student $student): RedirectResponse
    {
        $input = $request->validated();

        if (isset($input['password']) && $input['password']) {
            $input['password'] = bcrypt($input['password']);
        } else {
            unset($input['password']);
        }

        $message = $student->name.'학생 정보 수정';
        $student->setActivityLogOptions([
            'description' => $message,
        ])->update($input);

        // 프로필이미지 등록/삭제
        if (request()->remove_avatar) {
            $student->avatarRemove();
        }
        $student->avatarUpload();

        return to_route('admin.students.show', $student)->with('message', ['success', $message.'성공']);
    }

    /**
     * 삭제
     *
     * DELETE | admin/students/{student} | admin.students.destroy
     */
    public function destroy(Student $student): RedirectResponse
    {
        $message = $student->name.'학생 삭제';
        $student->setActivityLogOptions([
            'academy_id' => $student->academy_id,
            'is_show' => 1,
            'description' => $message,
        ])->delete();

        return to_route('admin.students.index')->with('message', ['success', $message.'성공']);
    }

    /**
     * 서비스 시작
     *
     * @param Student $student
     * @return JsonResponse
     */
    public function serviceStart(Student $student): JsonResponse
    {
        $message = $this->name.' 서비스시작';

        try {
            $result = $this->studentService->serviceStartOne($student);

            if (!$result['result']) {
                throw new Exception($result['message']);
            }


            return $this->sendResponse($result, $message.'성공');
        } catch (Exception $e) {
            return $this->sendError($message.'실패', $e->getMessage(), $e->getCode());
        }
    }

    /**
     * 학원변경
     *
     * @param Student $student
     * @return JsonResponse
     */
    public function academyChange(Student $student): JsonResponse
    {
        request()->validate([
            'new_academy_id' => 'required|integer',
        ]);

        $message = $this->name.' 학원변경';

        try {
            $result = $this->studentService->academyChange($student, request()->new_academy_id);

            if (!$result['result']) {
                throw new Exception($result['message'], $result['code']);
            }

            return $this->sendResponse($result['data'], $message.'성공');
        } catch (Exception $e) {
            return $this->sendError($message.'실패', $e->getMessage(), $e->getCode());
        }
    }


    /*
     * 운영자->학생로그인
     *
     * GET|HEAD | admin/students/{student}/login | admin.students.login
     */
    public function login(Student $student): RedirectResponse
    {
        auth('web')->login($student);

        activity(request()->route()->getName())
            ->causedBy(auth()->user())
            ->performedOn($student)
            ->log('관리자-학생로그인');

        //        $token = $student->createToken('admin-student-login')->plainTextToken;

        //        return redirect(config('dailykor.service.learn_app_url').'?token='.$token);
        return to_route('app.main')->with('message', ['success', '반갑습니다.']);
    }

    /**
     * 본사 > 학생상세 > 활동로그
     * GET|HEAD | admin/students/{student}/active-log | admin.students.active-log
     */
    public function activeLog(Student $student): Response
    {
        $yearMonthFilter = request()->input('filters')['month'] ?? null;
        $descriptionFilter = request()->input('filter_text');
        $student->load(['academy'])->append(['avatar'])->makeVisible('manager_memo');
        $activity = Activity::where(['causer_type' => Student::class, 'causer_id' => $student->id]);

        // 월 필터
        if ($yearMonthFilter) {
            $activity->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', $yearMonthFilter);
        }

        // 내용 필터
        if ($descriptionFilter) {
            $activity->where('description', 'like', '%'.$descriptionFilter.'%');
        }

        $activity->orderBy('id', 'desc');

        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 50;
        $collection = $activity->paginate($pageSize)->appends([
            'filters' => [
                'created_at' => request()->filters['created_at'] ?? null,
            ],
            'filter_text' => request()->filter_text
        ]);

        return Inertia::render('student/ActiveLog', [
            'collection' => ListCollection::collection($collection),
            'route_name' => request()->route()->getName(),
            'academies' => Academy::all()->pluck('name', 'id'),
            'student' => $student,
            'config' => [
                'dbcode' => [
                    'students' => config('dailykor.dbcode.students'),
                ],
            ],
            'page' => [
                'active' => 'admin.students.active-log',
                'title' => '활동로그',
                'breadcrumbs' => ['학생관리'],
            ],
        ]);
    }

    /**
     * 본사 > 학생상세 > 변경로그
     * GET|HEAD | admin/students/{student}/change-log | admin.students.change-log
     */
    public function changeLog(Student $student): Response
    {
        $yearMonthFilter = request()->input('filters')['month'] ?? null;
        $descriptionFilter = request()->input('filter_text');
        $student->load(['academy'])->append(['avatar'])->makeVisible('manager_memo');
        $activity = Activity::where(['subject_type' => Student::class, 'subject_id' => $student->id])
            ->where(function ($q) {
                $q->whereIn('causer_type', [Admin::class])
                    ->orWhere('causer_type', null);
            });

        // 월 필터
        if ($yearMonthFilter) {
            $activity->whereRaw('DATE_FORMAT(created_at, "%Y-%m") = ?', $yearMonthFilter);
        }

        // 내용 필터
        if ($descriptionFilter) {
            $activity->where('description', 'like', '%'.$descriptionFilter.'%');
        }

        $activity->orderBy('id', 'desc');

        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 50;
        $collection = $activity->paginate($pageSize)->appends([
            'filters' => [
                'created_at' => request()->filters['created_at'] ?? null,
            ],
            'filter_text' => request()->filter_text
        ]);

        return Inertia::render('student/ChangeLog', [
            'collection' => ListCollection::collection($collection),
            'route_name' => request()->route()->getName(),
            'academies' => Academy::all()->pluck('name', 'id'),
            'student' => $student,
            'config' => [
                'dbcode' => [
                    'students' => config('dailykor.dbcode.students'),
                ],
            ],
            'page' => [
                'active' => 'admin.students.change-log',
                'title' => '변경로그',
                'breadcrumbs' => ['학생관리'],
            ],
        ]);
    }

    /**
     * 본사 > 학생상세 > 학습내역
     * GET|HEAD | admin/students/{student}/learning-history | admin.students.learning-history
     */
    public function learningHistory(Student $student): Response
    {
        $student->load(['academy'])->append(['avatar'])->makeVisible('manager_memo');
        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 50;
        $descriptionFilter = request()->input('filter_text');
        $filters = request()->filters;
        $trainingResultQuery = $student->training_results()
            ->whereNotNull('completed_at')
            ->with('training')
            ->with('curriculum');

        if (isset($filters['grade'])) {
            $trainingResultQuery->whereHas('student', function ($query) use ($filters) {
                $query->where('grade', $filters['grade']);
            });
        }

        if (isset($filters['term'])) {
            $trainingResultQuery->whereHas('student', function ($query) use ($filters) {
                $query->where('term', $filters['term']);
            });
        }

        if (isset($filters['stage'])) {
            $trainingResultQuery->whereHas('training', function ($query) use ($filters) {
                $query->where('stage', $filters['stage']);
            });
        }

        if (isset($filters['round'])) {
            $trainingResultQuery->where('round', $filters['round']);
        }

        if (isset($filters['month'])) {
            $trainingResultQuery->whereRaw('DATE_FORMAT(completed_at, "%Y-%m") = ?', $filters['month']);
        }

        // 내용 필터
        if ($descriptionFilter) {
            $trainingResultQuery->whereHas('curriculum', function ($query) use ($descriptionFilter) {
                $query->where('name', 'like', '%'.$descriptionFilter.'%');
            });
        }

        $trainingResultQuery->orderBy('id', 'desc');

        $trainingResult = $trainingResultQuery->paginate($pageSize)->appends([
                'filters' => [
                    'created_at' => request()->filters['created_at'] ?? null,
                ],
                'filter_text' => request()->filter_text
            ]);

        $trainingResult->each(function (TrainingResult $value) {
            $ancestors = $value->curriculum->ancestors()->get();

            // 조상 노드가 있는지 확인하고 분할합니다.
            if ($ancestors->count() > 1) {
                $ancestorsNames = explode('-', $ancestors[1]->name);
                $value->curriculum->grade = $ancestorsNames[0];
                $value->curriculum->term = $ancestorsNames[1];
            }

            $value->curriculum->bigUnit = $value->curriculum->ancestors[2]['name'];
        });

        $collection = $trainingResult;

        return Inertia::render('student/LearningHistory', [
            'collection' => ListCollection::collection($collection),
            'route_name' => request()->route()->getName(),
            'academies' => Academy::all()->pluck('name', 'id'),
            'student' => $student,
            'config' => [
                'dbcode' => [
                    'students' => config('dailykor.dbcode.students'),
                    'trainings' => config('dailykor.dbcode.trainings'),
                    'training_results' => config('dailykor.dbcode.training_results'),
                ],
            ],
            'page' => [
                'active' => 'admin.students.learning-history',
                'title' => '학습내역',
                'breadcrumbs' => ['학생관리'],
            ],
        ]);
    }

    /**
     * 본사 > 학생상세 > 학습보고서
     * GET|HEAD | admin/students/{student}/learning-report | admin.students.learning-report
     */
    public function learningReport(Student $student): Response
    {
        //todo 작업
        $descriptionFilter = request()->input('filter_text');
        $student->load(['academy'])->append(['avatar'])->makeVisible('manager_memo');
        $testResults = $student->test_results()
            ->with('test')
            ->whereNotNull('completed_at');

        if (isset($filters['month'])) {
            $testResults->whereRaw('DATE_FORMAT(completed_at, "%Y-%m") = ?', $filters['month']);
        }

        $testResults->orderBy('id', 'desc');

        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 50;
        $collection = $testResults->paginate($pageSize)->appends([
            'filters' => [
                'created_at' => request()->filters['created_at'] ?? null,
            ],
            'filter_text' => request()->filter_text
        ]);

        return Inertia::render('student/LearningReport', [
            'collection' => ListCollection::collection($collection),
            'route_name' => request()->route()->getName(),
            'academies' => Academy::all()->pluck('name', 'id'),
            'student' => $student,
            'config' => [
                'dbcode' => [
                    'students' => config('dailykor.dbcode.students'),
                ],
            ],
            'page' => [
                'active' => 'admin.students.active-log',
                'title' => '학습보고서',
                'breadcrumbs' => ['학생관리'],
            ],
        ]);
    }
}
