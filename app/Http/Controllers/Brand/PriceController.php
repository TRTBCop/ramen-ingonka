<?php

namespace App\Http\Controllers\Brand;

use App\Http\Controllers\BaseController;
use Inertia\Inertia;
use Inertia\Response;

class PriceController extends BaseController
{
    public function __construct()
    {
    }

    public function index(): Response
    {
        return Inertia::render('price/Index', [
            'products' => config('dailykor.payment.products')
        ]);
    }
}
