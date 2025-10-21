<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Inertia\Inertia;

class LoginController extends Controller
{
    public function loginPage()
    {
        return Inertia::render('Login');
    }
}
