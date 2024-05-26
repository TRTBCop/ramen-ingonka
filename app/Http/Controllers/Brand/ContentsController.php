<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\BaseController;
use Inertia\Inertia;
use Inertia\Response;

class ContentsController extends BaseController
{
    public function __construct()
    {
    }

    public function index(): Response
    {
        return Inertia::render('contents/Index', []);
    }
}
