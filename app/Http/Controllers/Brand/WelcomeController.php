<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\BaseController;
use Inertia\Inertia;
use Inertia\Response;

class WelcomeController extends BaseController
{
    public function __construct()
    {
    }

    public function __invoke(): Response
    {
        return Inertia::render('Index', []);
    }
}
