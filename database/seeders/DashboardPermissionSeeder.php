<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DashboardPermissionSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            ['dashboard.access', 'ورود به پنل مدیریت', 'داشبورد'],
            ['dashboard.view', 'مشاهده داشبورد', 'داشبورد'],
            ['users.view', 'مشاهده کاربران', 'کاربران و دسترسی'],
            ['users.create', 'ایجاد کاربر', 'کاربران و دسترسی'],
            ['users.update', 'ویرایش کاربر', 'کاربران و دسترسی'],
            ['users.block', 'فعال یا غیرفعال کردن کاربر', 'کاربران و دسترسی'],
            ['users.assign_roles', 'تغییر نقش کاربر', 'کاربران و دسترسی'],
            ['roles.view', 'مشاهده نقش‌ها و مجوزها', 'کاربران و دسترسی'],
            ['roles.update', 'تغییر مجوزهای نقش', 'کاربران و دسترسی'],
            ['products.view', 'مشاهده محصولات', 'فروشگاه'],
            ['products.create', 'ایجاد محصول', 'فروشگاه'],
            ['products.update', 'ویرایش محصول', 'فروشگاه'],
            ['products.delete', 'حذف محصول', 'فروشگاه'],
            ['categories.manage', 'مدیریت دسته‌بندی‌ها', 'فروشگاه'],
            ['discounts.manage', 'مدیریت تخفیف‌ها', 'فروشگاه'],
            ['storage.manage', 'مدیریت فضای ذخیره‌سازی', 'سیستم'],
            ['wallets.view', 'مشاهده کیف پول کاربران', 'مالی'],
            ['wallets.adjust', 'تعدیل موجودی کیف پول', 'مالی'],
        ];

        foreach ($permissions as [$name, $label, $group]) {
            DB::table('permissions')->updateOrInsert(
                ['name' => $name],
                ['label' => $label, 'group_name' => $group, 'updated_at' => now(), 'created_at' => now()]
            );
        }

        $permissionIds = DB::table('permissions')->pluck('id', 'name');

        $staffPermissions = [
            'dashboard.access', 'dashboard.view',
            'users.view',
            'products.view', 'products.create', 'products.update',
            'categories.manage', 'discounts.manage',
        ];

        foreach ($staffPermissions as $name) {
            if (!isset($permissionIds[$name])) {
                continue;
            }

            DB::table('role_permissions')->updateOrInsert(
                ['role' => 'staff', 'permission_id' => $permissionIds[$name]],
                ['updated_at' => now(), 'created_at' => now()]
            );
        }
    }
}