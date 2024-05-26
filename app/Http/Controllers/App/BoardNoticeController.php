<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\BaseController;
use App\Http\Resources\ListCollection;
use App\Models\BoardNotice;
use Inertia\Inertia;
use Inertia\Response;

class BoardNoticeController extends BaseController
{
    /**
     * 공지사항 목록
     * GET|HEAD | app/board-notices | app.board-notices.index
     *
     * @return Response
     */
    public function index(): Response
    {
        $view = 'board-notices/Index';

        $query = BoardNotice::listFilter();
        $pageSize = 10;
        $collection = $query->paginate($pageSize);


        return Inertia::render($view, [
            'collection' => ListCollection::collection($collection),
        ]);
    }

    /**
     * 공지사항 상세
     * GET|HEAD | app/board-notices/{id} | app.board-notices.show
     *
     * @return Response
     */
    public function show(int $id): Response
    {
        $view = 'board-notices/Show';

        $notice = BoardNotice::find($id);

        return Inertia::render($view, [
            'notice' => $notice
        ]);
    }
}
