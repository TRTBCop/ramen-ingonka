<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\BaseController;
use Illuminate\Http\RedirectResponse;
use Inertia\Inertia;
use Inertia\Response;

class ComingSoonController extends BaseController
{
    public function __construct()
    {
    }


    /**
     * 준비중 페이지
     * GET|HEAD | app/coming-soon | coming-soon.show
     * @return RedirectResponse|Response
     */
    public function show(): Response|RedirectResponse
    {
        return Inertia::render('coming-soon/Show', []);
    }
}
