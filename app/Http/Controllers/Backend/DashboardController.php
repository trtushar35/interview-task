<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class DashboardController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:overview-dashboard')->only(['index']);
    }

    public function index()
    {
        return Inertia::render('Dashboard');
    }
}
