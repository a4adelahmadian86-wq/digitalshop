<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class AdminPermissionMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        $user = $request->user();
        abort_unless($user, 403);

        $route = (string) optional($request->route())->getName();
        $permission = $this->permissionFor($route);

        if ($permission && !$user->hasPermission($permission)) {
            abort(403, 'شما اجازه دسترسی به این بخش را ندارید.');
        }

        return $next($request);
    }

    private function permissionFor(string $route): ?string
    {
        $map = [
            'admin.users' => 'users.manage',
            'admin.roles' => 'permissions.manage',
            'admin.categories' => 'products.categories',
            'admin.products' => 'products.manage',
            'admin.products.approvals' => 'products.approve',
            'admin.content' => 'content.manage',
            'admin.blog' => 'content.blog',
            'admin.support' => 'support.manage',
            'admin.ai' => 'ai.manage',
            'admin.storage' => 'storage.manage',
            'admin.discounts' => 'marketing.discounts',
            'admin.wallets' => 'finance.wallets',
            'admin.module' => 'admin.modules',
            'admin.newsletter' => 'newsletter.manage',
            'admin.sms' => 'sms.manage',
            'admin.settings' => 'settings.manage',
            'admin.dashboard' => 'dashboard.view',
        ];

        foreach ($map as $prefix => $permission) {
            if ($route === $prefix || str_starts_with($route, $prefix . '.')) {
                return $permission;
            }
        }

        return 'admin.modules';
    }
}
