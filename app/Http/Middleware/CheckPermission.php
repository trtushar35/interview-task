<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\UserService;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    protected $userService;

    public function __construct(UserService $userService)
    {
        $this->userService = $userService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next, string $permission): Response
    {
        $user = Auth::guard('admin')->user();

        if (!$user) {
            return redirect()->route('backend.login.page')
                ->with('errMsg', 'Please login first.');
        }

        // Check if user has the required permission using UserService
        if (!$this->userService->hasPermission($user, $permission)) {
            abort(403, 'You do not have permission to access this resource.');
        }

        return $next($request);
    }
}