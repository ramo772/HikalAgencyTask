<?php

namespace App\Http\Controllers\WebService\Dashboard;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    function __invoke()
    {
        return Inertia::render('Dashboard');
    }
}
