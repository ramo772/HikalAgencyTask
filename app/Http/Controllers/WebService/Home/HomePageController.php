<?php

namespace App\Http\Controllers\WebService\Home;

use App\Http\Controllers\Controller;
use Illuminate\Console\Application;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class HomePageController extends Controller
{

    function __invoke()
    {
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
        ]);
    }
}
