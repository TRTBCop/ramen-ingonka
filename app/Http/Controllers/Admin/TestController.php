<?php

namespace App\Http\Controllers\Admin;

use App\Enums\RoleEnum;
use App\Http\Controllers\BaseController;
use App\Http\Requests\TestRequest;
use App\Models\Test;
use Illuminate\Http\RedirectResponse;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;
use Inertia\Response;

/**
 * @group Admin 관리자관리
 * @authenticated
 *
 * APIs for managing users
 */
class TestController extends BaseController
{
    public function __construct(
        public string $name = '진단관리',
    ) {
        $this->middleware(['permission:contents']);
    }

    /**
     * 목록
     * GET|HEAD | admin/tests | admin.tests.index
     *
     */
    public function index(): Response
    {
        $collection = $this->getCollection(Test::class);

        return Inertia::render('tests/Index', [
            'collection' => $collection,
            'route_name' => request()->route()->getName(),
            'roles' => collect(RoleEnum::admin())->map(fn ($v) => [
                'text' => $v->text(),
                'name' => $v->name,
                'value' => $v->value,
            ]),
            'page' => [
                'active' => 'admin.tests.index',
                'title' => $this->name.'목록',
                'breadcrumbs' => [$this->name],
            ],
        ]);
    }

    /**
     * 상세
     *
     * GET|HEAD | admin/tests/{test} | admin.tests.show
     */
    public function show(Test $test): Response
    {
        $questions = $test->questions()->get()->keyBy('id');

        // contents <=> question 연결
        $contents = $test->contents;
        foreach ((array)$contents['questions'] as $key => $contentQuestion) {
            if ($contentQuestion['id'] && isset($questions[$contentQuestion['id']])) {
                $contents['questions'][$key]['question'] = $questions[$contentQuestion['id']]->toArray();
            }
        }
        $test->contents = $contents;

        return Inertia::render('tests/Show', [
            'test' => $test,
            'roles' => collect(RoleEnum::admin())->map(fn ($v) => [
                'text' => $v->text(),
                'name' => $v->name,
                'value' => $v->value,
            ]),
            'config' => [
                'code' => [
                    'test' => config('dailykor.test'),
                ],
            ],
            'page' => [
                'active' => 'admin.tests.index',
                'title' => $this->name.' 상세',
                'breadcrumbs' => [$this->name],
            ],
        ]);
    }

    /**
     * 수정
     *
     * PUT|PATCH | admin/tests/{test} | admin.tests.update
     */
    public function update(TestRequest $request, Test $test): RedirectResponse
    {
        $message = '수정';
        $input = $request->all();
        $input['is_published'] = $input['is_published'] ?? false;
        if (!$test->published_at && $input['is_published']) {
            $input['published_at'] = now();
        } elseif ($test->published_at && !$input['is_published']) {
            $input['published_at'] = null;
        }

        $test->setActivityLogOptions([
            'description' => $message,
            'is_show' => 1,
        ])->update($input);

        return to_route('admin.tests.show', $test)->with('message', ['success', $message.'성공']);
    }


    /**
     * 삭제 (hard)
     *
     * DELETE | admin/tests/{test} | admin.tests.destroy
     */
    public function destroy(Test $test): RedirectResponse
    {
        $message = '삭제';

        // 등록된 문제가 있다면 삭제불가
        if ($test->questions()->count()) {
            return redirect()->back()->with('message', ['error', __('messages.admin.tests.child_exists_cannot_delete')]);
        }


        $test->setActivityLogOptions([
            'description' => $message,
            'is_show' => 1,
        ])->delete();

        // 이미지 제거
        $path = 'test/'.$test->id.'/';
        $files = Storage::disk('s3')->files($path, true);
        foreach ($files as $file) {
            Storage::disk('s3')->delete($file);
        }

        return to_route('admin.tests.index')->with('message', ['success', $message.'성공']);
    }
}
