<?php

namespace App\Http\Controllers\Backend;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Inertia\Inertia;

class LoginController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    public function loginPage()
    {
        return Inertia::render('Login');
    }

    public function loginPost(LoginRequest $request)
    {
        $request->validated();

        $userInfo = $this->userService->userExists($request->email);

        if (!empty($userInfo)) {
            if ($userInfo->status != "Active") {
                return Inertia::render('Login')->with('errorMessage', 'Your Account Temporary Blocked. Please Contact Administrator.');
            }

            if (Hash::check($request->password, $userInfo->password)) {
                // Log the user in using the admin guard
                Auth::guard('admin')->login($userInfo);

                return redirect()->route('backend.dashboard')->with('successMessage', 'Logged In Successfully');
            } else {
                return Inertia::render('Login')->with('warningMessage', 'Wrong Password. Please Enter Valid Password.');
            }
        } else {
            return Inertia::render('Login')->with('warningMessage', 'Invalid Username. Please Enter Valid Username.');
        }
    }

    public function logout()
    {
        Auth::guard('admin')->logout();
        return redirect()->route('backend.login.page')->with('successMessage', 'Logged out successfully.');
    }
}