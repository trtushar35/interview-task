<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Services\MenuService;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
    protected $menuService;

    public function __construct(MenuService $menuService)
    {
        $this->menuService = $menuService;
    }

    /**
     * Handle an incoming request.
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (Auth::guard('admin')->check() && Auth::guard('admin')->user()->status == 'Active') {
            
            // Share menus with Inertia for authenticated admin users
            $user = Auth::guard('admin')->user();
            $menus = $this->menuService->getUserMenus($user);
            
            // Share menus with all Inertia responses
            if (class_exists('Inertia\Inertia')) {
                \Inertia\Inertia::share([
                    'menus' => $menus,
                ]);
            }
            
            return $next($request);
        }

        session()->flash('errMsg', 'Please Login First.');
        return redirect()->route('backend.login.page');
    }
}