<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class RoleMiddleware
{
    public function handle(Request $request, Closure $next, ...$roles): Response
    {
        $user = $request->user();

        if (!$user) {
            return redirect()->route('login');
        }

        if (!$user->is_active) {
            abort(403, 'حساب کاربری شما فعال نیست.');
        }

        // Administrators always retain access. Other roles may enter the
        // existing /admin route group only when an explicit permission grants it.
        if ($user->isAdmin()) {
            return $next($request);
        }

        if ($roles && in_array($user->role, $roles, true)) {
            return $next($request);
        }

        $permission = $this->permissionForRoute($request->route()?->getName());

        abort_unless(
            $permission !== null && $user->hasPermission($permission),
            403,
            'شما اجازه دسترسی به این بخش را ندارید.'
        );

        return $next($request);
    }

    private function permissionForRoute(?string $route): ?string
    {
        if (!$route || !str_starts_with($route, 'admin.')) {
            return null;
        }

        return match (true) {
            $route === 'admin.dashboard' => 'dashboard.view',
            $route === 'admin.logout' => 'dashboard.access',
            str_starts_with($route, 'admin.users.') => match (true) {
                str_ends_with($route, '.create'), str_ends_with($route, '.store') => 'users.create',
                str_ends_with($route, '.edit'), str_ends_with($route, '.update') => 'users.update',
                str_ends_with($route, '.toggle') => 'users.block',
                default => 'users.view',
            },
            str_starts_with($route, 'admin.categories.') => 'categories.manage',
            str_starts_with($route, 'admin.products.') => match (true) {
                str_ends_with($route, '.create'), str_ends_with($route, '.store') => 'products.create',
                str_ends_with($route, '.edit'), str_ends_with($route, '.update') => 'products.update',
                str_ends_with($route, '.destroy') => 'products.delete',
                default => 'products.view',
            },
            str_starts_with($route, 'admin.storage.') => 'storage.manage',
            str_starts_with($route, 'admin.discounts.') => 'discounts.manage',
            str_starts_with($route, 'admin.wallets.') => match (true) {
                str_ends_with($route, '.credit'), str_ends_with($route, '.debit') => 'wallets.adjust',
                default => 'wallets.view',
            },
            default => null,
        };
    }
}
