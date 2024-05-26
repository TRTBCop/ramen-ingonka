<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\BaseController;
use Inertia\Inertia;
use Inertia\Response;

class PolicyController extends BaseController
{
    public function __construct()
    {
    }

    public function terms(): Response
    {
        return Inertia::render('policy/Terms', []);
    }

    public function privacy(): Response
    {
        return Inertia::render('policy/Privacy', );
    }
}
