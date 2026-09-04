<?php

namespace Database\Seeders;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Database\Seeder;

class AccessControlSeeder extends Seeder
{
    public function run(): void
    {
        $permissions = [
            'dashboard.view'=>'مشاهده پیشخوان','dashboard.configure'=>'تنظیم المان‌های پیشخوان',
            'users.manage'=>'مدیریت کاربران','permissions.manage'=>'نقش‌ها و دسترسی‌ها',
            'products.manage'=>'مدیریت محصولات','products.approve'=>'تأیید محصولات','products.categories'=>'دسته‌بندی‌ها',
            'orders.manage'=>'مدیریت سفارش‌ها','finance.manage'=>'مدیریت مالی','finance.wallets'=>'مدیریت کیف پول‌ها',
            'subscriptions.manage'=>'مدیریت اشتراک‌ها','assets.manage'=>'مدیریت فایل‌ها و دارایی‌ها','reader.manage'=>'مدیریت خوانشگر',
            'loyalty.manage'=>'مدیریت باشگاه مشتریان','content.manage'=>'مدیریت محتوا','content.blog'=>'مدیریت وبلاگ',
            'marketing.discounts'=>'مدیریت تخفیف‌ها','marketing.manage'=>'مدیریت بازاریابی','ai.manage'=>'مدیریت هوش مصنوعی',
            'support.manage'=>'مدیریت پشتیبانی','notifications.manage'=>'مدیریت اعلان‌ها','reports.manage'=>'گزارش‌ها و تحلیل‌ها',
            'storage.manage'=>'مدیریت ذخیره‌سازی','search.manage'=>'مدیریت جستجو','sellers.manage'=>'مدیریت فروشندگان',
            'security.manage'=>'مدیریت امنیت','settings.manage'=>'مدیریت تنظیمات','system.manage'=>'مدیریت سیستم',
            'newsletter.manage'=>'مدیریت خبرنامه','sms.manage'=>'مدیریت پیامک و رمز یکبارمصرف','admin.modules'=>'دسترسی به ماژول‌های مدیریت',
        ];

        foreach ($permissions as $name=>$label) {
            [$group] = array_pad(explode('.', $name), 1, 'general');
            Permission::updateOrCreate(['name'=>$name], ['label'=>$label,'group_name'=>$group]);
        }

        $admin = Role::firstOrCreate(['name'=>'admin'], ['label'=>'مدیر سامانه','description'=>'دسترسی کامل سامانه','is_system'=>true]);
        $admin->permissions()->sync(Permission::pluck('id')->all());

        $defaults = [
            'buyer'=>['dashboard.view'],
            'seller'=>['dashboard.view','products.manage','orders.manage','assets.manage'],
        ];
        foreach ($defaults as $name=>$items) {
            $role=Role::firstOrCreate(['name'=>$name],['label'=>$name==='seller'?'فروشنده':'خریدار','is_system'=>true]);
            $role->permissions()->sync(Permission::whereIn('name',$items)->pluck('id')->all());
        }
    }
}
