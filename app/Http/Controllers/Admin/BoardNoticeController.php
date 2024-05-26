<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\BaseController;
use App\Http\Requests\BoardNoticeRequest;
use App\Http\Resources\ListCollection;
use App\Models\BoardNotice;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileDoesNotExist;
use Spatie\MediaLibrary\MediaCollections\Exceptions\FileIsTooBig;

class BoardNoticeController extends BaseController
{
    public function __construct(
        public string $name = '공지사항'
    ) {
        $this->middleware(['permission:manager']);
    }

    /**
     * 목록
     *
     * GET|HEAD | admin/board-notices | admin.board-notices.index
     *
     * @return Response
     */
    public function index(): Response
    {
        $query = BoardNotice::listFilter();
        $pageSize = (isset(request()->pageSize) && is_numeric(request()->pageSize)) ? request()->pageSize : 50;
        $collection = $query->paginate($pageSize);

        return Inertia::render('board-notices/Index', [
            'collection' => ListCollection::collection($collection),
            'config' => [
                'board' => dbcode('board_notices'),
            ],
            'route_name' => request()->route()->getName(),
            'page' => [
                'active' => 'admin.board-notices.index',
                'title' => $this->name.' 목록',
                'breadcrumbs' => ['게시판관리'],
            ],
        ]);
    }

    /**
     * 상세
     *
     * GET|HEAD | admin/board-notices/{board_notice} | admin.board-notices.show
     */
    public function show(BoardNotice $boardNotice): Response
    {
        $boardNotice->load('media')->append('files');

        return Inertia::render('board-notices/Show', [
            'config' => [
                'board' => dbcode('board_notices'),
            ],
            'board' => $boardNotice,
            'route_name' => request()->route()->getName(),
            'page' => [
                'active' => 'admin.board-notices.index',
                'title' => $this->name.' 상세',
                'breadcrumbs' => ['게시판관리'],
            ],
        ]);
    }


    /**
     * 등록 Form
     *
     * GET|HEAD | admin/board-notices/create  | admin.board-notices.create
     * @return Response
     */
    public function create(): Response
    {
        return Inertia::render('board-notices/Create', [
            'config' => [
                'board' => dbcode('board_notices'),
            ],
            'route_name' => request()->route()->getName(),
            'page' => [
                'active' => 'admin.board-notices.index',
                'title' => $this->name.' 작성',
                'breadcrumbs' => ['게시판관리'],
            ],
        ]);
    }

    /**
     * 등록
     *
     * POST | admin/board-notices | admin.board-notices.store
     */
    public function store(BoardNoticeRequest $request): RedirectResponse
    {
        $boardNotice = new BoardNotice();
        parent::setStore($this, $boardNotice, $request);

        return to_route('admin.board-notices.show', $boardNotice)->with('message', ['success', $this->name.'등록성공']);
    }

    /**
     * 수정
     *
     * PUT|PATCH | admin/board-notices/{board_notice} | admin.board-notices.update
     */
    public function update(BoardNoticeRequest $request, BoardNotice $boardNotice): RedirectResponse
    {
        $messageType = $request->isMethod('POST') ? '등록' : '수정';

        $input = $request->all();
        $input['admin_id'] = auth()->user()->id;

        $input['is_published'] = $input['is_published'] ?? false;
        if (!$boardNotice->published_at && $input['is_published']) {
            $input['published_at'] = now();
        } elseif ($boardNotice->published_at && !$input['is_published']) {
            $input['published_at'] = null;
        }
        $oldHtml = $boardNotice->contents ?? '';

        $input['scope'] = array_sum($request->scope ?? []);
        $message = $this->name.' '.$messageType;
        $boardNotice->fill($input)->setActivityLogOptions([
            'description' => $message,
        ])->save();

        $input['contents'] = fileTempMove($input['contents'], $oldHtml, '/board-notices/'.$boardNotice->id); // 에디터가 있는 컨텐츠의경우 이미지 변환
        if ($input['contents'] != $boardNotice->contents) {
            $boardNotice->fill($input)->setActivityLogOptions([
                'description' => $message,
            ])->save();
        }

        if ($request->file('files')) {
            foreach ($request->file('files') as $file) {
                try {
                    $boardNotice->addMedia($file)->toMediaCollection('file');
                } catch (FileDoesNotExist|FileIsTooBig $e) {
                }
            }
        }

        if ($request->del_files) {
            $boardNotice->media()->whereIn('id', $request->del_files)->delete();
        }

        $boardNotice->refresh();

        return to_route('admin.board-notices.show', $boardNotice)->with('message', ['success', $message.'성공']);
    }

    /**
     * 삭제
     *
     * DELETE admin/board-notices/{board_notice} admin.board-notices.destroy
     */
    public function destroy(BoardNotice $boardNotice): RedirectResponse
    {
        $message = $this->name.' 삭제';
        $boardNotice->setActivityLogOptions([
            'description' => $message,
        ])->delete();

        return to_route('admin.board-notices.index')->with('message', ['success', $message.'성공']);
    }
}
