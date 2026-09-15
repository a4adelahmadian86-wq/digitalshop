<?php

namespace App\Http\Controllers;

use App\Models\AuditLog;
use App\Models\Permission;
use App\Models\User;
use App\Services\AuditLogger;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class AdminAccessController extends Controller
{
    public function index()
    {
        $permissions = Permission::query()->orderBy('group_name')->orderBy('name')->get()->groupBy('group_name');
        $roles = User::query()->select('role')->distinct()->pluck('role')
            ->merge(DB::table('role_permissions')->distinct()->pluck('role'))
            ->merge(['staff', 'buyer', 'user', 'admin'])
            ->filter()->unique()->sort()->values();
        $assignments = DB::table('role_permissions')
            ->join('permissions', 'permissions.id', '=', 'role_permissions.permission_id')
            ->select('role_permissions.role', 'permissions.name')
            ->get()->groupBy('role')
            ->map(fn ($items) => $items->pluck('name')->values()->all());
        $recentAudits = AuditLog::with('actor')->latest()->limit(15)->get();
        return view('admin.access.index', compact('permissions', 'roles', 'assignments', 'recentAudits'));
    }

    public function update(Request $request, string $role, AuditLogger $auditLogger)
    {
        abort_if($role === 'admin', 403, 'مجوزهای مدیر ارشد قابل محدودسازی از این صفحه نیست.');

        $valid = Permission::query()->pluck('id', 'name');
        $selected = collect($request->input('permissions', []))
            ->filter(fn ($name) => isset($valid[$name]))->unique()->values();

        if ($selected->contains('dashboard.access') && !$selected->contains('dashboard.view')) {
            return back()->withErrors(['permissions' => 'برای ورود به پنل، مجوز مشاهده داشبورد نیز لازم است.'])->withInput();
        }

        DB::transaction(function () use ($role, $selected, $valid) {
            DB::table('role_permissions')->where('role', $role)->delete();
            $rows = $selected->map(fn ($name) => [
                'role' => $role, 'permission_id' => $valid[$name],
                'created_at' => now(), 'updated_at' => now(),
            ])->all();
            if ($rows) DB::table('role_permissions')->insert($rows);
        });

        $auditLogger->record($request, 'role.permissions.updated', null, [
            'role' => $role, 'permissions' => $selected->all(),
        ]);
        return back()->with('success', "مجوزهای نقش {$role} بروزرسانی شد.");
    }
}