<?php

namespace App\Http\Controllers;

use Inertia\Inertia;
use Inertia\Response;

class HtmlController extends BaseController
{
    public function __construct() {}

    public function __invoke($page = null): Response
    {

        $component = '/Index';

        if ($page) {
            $component = str_replace('-', '/', $page);
        }

        return Inertia::render($component, []);
    }
}
