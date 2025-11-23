<?php

namespace App\Http\Controllers\Landing;

use App\Http\Controllers\Controller;
use Illuminate\View\View;

class ServicesController extends Controller
{
    public function __invoke(): View
    {
        return view('landing.services');
    }
}
