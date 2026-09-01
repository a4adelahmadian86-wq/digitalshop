<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {
        $admin = auth('admin')->user();

        if (!$admin) {
            return redirect()->route('admin.phone');
        }

        if (!$admin->is_active) {
            auth('admin')->logout();

            return redirect()
                ->route('admin.phone')
                ->withErrors([
                    'phone' => 'حساب مدیریت شما غیرفعال است.',
                ]);
        }

        return $next($request);
    }
}<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminAuth
{
    public function handle(
        Request $request,
        Closure $next
    ): Response {

        /*
         * پنل مدیریت فقط با Guard اختصاصی admin
         * احراز هویت می‌شود.
         */
        if (
            !auth('admin')->check()
        ) {
            return redirect()
                ->route('admin.phone');
        }

        $admin =
            auth('admin')->user();

        /*
         * اطمینان از فعال بودن حساب مدیر
         */
        if (
            !$admin ||
            !$admin->is_active
        ) {
            auth('admin')->logout();

            $request->session()->invalidate();

            $request->session()->regenerateToken();

            abort(
                403,
                'حساب مدیریتی شما فعال نیست.'
            );
        }

        return $next($request);
    }
}