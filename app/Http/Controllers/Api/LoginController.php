<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LoginRequest;
use App\Services\UserService;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle user login (API)
     */
    public function loginPost(LoginRequest $request)
    {
        $request->validated();

        $userInfo = $this->userService->userExists($request->email);

        if (!$userInfo) {
            return response()->json([
                'status' => 'error',
                'message' => 'Invalid username. Please enter a valid username.'
            ], 404);
        }

        if ($userInfo->status !== "Active") {
            return response()->json([
                'status' => 'error',
                'message' => 'Your account is temporarily blocked. Please contact the administrator.'
            ], 403);
        }

        if (!Hash::check($request->password, $userInfo->password)) {
            return response()->json([
                'status' => 'error',
                'message' => 'Wrong password. Please enter a valid password.'
            ], 401);
        }

        // Login the user using the admin guard
        Auth::guard('admin')->login($userInfo);

        // Optionally generate an API token (if using Sanctum)
        if (method_exists($userInfo, 'createToken')) {
            $token = $userInfo->createToken('API Token')->plainTextToken;
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Logged in successfully.',
            'user' => $userInfo,
            'token' => $token ?? null
        ], 200);
    }

    /**
     * Handle user logout (API)
     */
    public function logout(Request $request)
    {
        $user = Auth::guard('admin')->user();

        if ($user) {
            // If using Sanctum
            if ($request->user()) {
                $request->user()->currentAccessToken()->delete();
            }

            Auth::guard('admin')->logout();

            return response()->json([
                'status' => 'success',
                'message' => 'Logged out successfully.'
            ], 200);
        }

        return response()->json([
            'status' => 'error',
            'message' => 'No user is currently logged in.'
        ], 401);
    }
}
