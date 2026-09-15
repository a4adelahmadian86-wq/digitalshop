<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class DashboardAccessMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->is_active) {
            abort(403, 'حساب کاربری شما فعال نیست.');
        }

        abort_unless($user->isAdmin() || $user->hasPermission('dashboard.access'), 403, 'شما اجازه ورود به پنل مدیریت را ندارید.');

        return $next($request);
    }
}