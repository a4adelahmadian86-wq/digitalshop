<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('roles', function (Blueprint $table) {
            $table->id();
            $table->string('name', 80)->unique();
            $table->string('label', 120);
            $table->string('description', 255)->nullable();
            $table->boolean('is_system')->default(false);
            $table->timestamps();
        });

        Schema::create('permissions', function (Blueprint $table) {
            $table->id();
            $table->string('name', 120)->unique();
            $table->string('label', 160);
            $table->string('group_name', 80)->index();
            $table->timestamps();
        });

        Schema::create('permission_role', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('permission_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'permission_id']);
        });

        Schema::create('role_user', function (Blueprint $table) {
            $table->foreignId('role_id')->constrained()->cascadeOnDelete();
            $table->foreignId('user_id')->constrained()->cascadeOnDelete();
            $table->primary(['role_id', 'user_id']);
        });

        $roles = [
            ['name'=>'admin','label'=>'مدیر اصلی','description'=>'دسترسی کامل مدیریتی','is_system'=>true],
            ['name'=>'seller','label'=>'فروشنده','description'=>'مدیریت محصولات و فروش خود','is_system'=>true],
            ['name'=>'buyer','label'=>'خریدار','description'=>'خرید، دانلود و باشگاه مشتریان','is_system'=>true],
        ];
        foreach ($roles as $role) {
            DB::table('roles')->insert(array_merge($role, ['created_at'=>now(),'updated_at'=>now()]));
        }

        $groups = [
            'dashboard'=>'داشبورد','products'=>'محصولات','orders'=>'سفارش‌ها','users'=>'کاربران','categories'=>'دسته‌بندی‌ها',
            'discounts'=>'کدهای تخفیف','notifications'=>'اعلانات','ai'=>'هوش مصنوعی','storage'=>'ذخیره‌سازی','customers'=>'مشتریان',
        ];
        $permissions = [
            ['dashboard.view','مشاهده داشبورد','dashboard'],
            ['products.view','مشاهده محصولات','products'],['products.create','ایجاد محصول','products'],['products.edit','ویرایش محصول','products'],['products.delete','حذف محصول','products'],['products.approve','تأیید محصول','products'],
            ['orders.view','مشاهده سفارش‌ها','orders'],['orders.manage','مدیریت سفارش‌ها','orders'],
            ['users.view','مشاهده کاربران','users'],['users.create','ایجاد کاربر','users'],['users.edit','ویرایش کاربر','users'],['users.roles','مدیریت نقش‌ها و دسترسی‌ها','users'],
            ['categories.manage','مدیریت دسته‌بندی‌ها','categories'],
            ['discounts.manage','مدیریت کدهای تخفیف','discounts'],['discounts.ai','پیشنهاد کد تخفیف با AI','discounts'],
            ['notifications.manage','مدیریت اعلانات','notifications'],['notifications.sms','ارسال پیامک تبلیغاتی','notifications'],
            ['ai.view','مشاهده ابزارهای AI','ai'],['ai.feedback','مدیریت بازخورد AI','ai'],['ai.knowledge','مدیریت دانش محصولات','ai'],
            ['storage.manage','مدیریت Storage Provider','storage'],
            ['customers.view','مشاهده رفتار و مشتریان','customers'],
        ];
        foreach ($permissions as [$name,$label,$group]) {
            DB::table('permissions')->insert(['name'=>$name,'label'=>$label,'group_name'=>$group,'created_at'=>now(),'updated_at'=>now()]);
        }
        $admin = DB::table('roles')->where('name','admin')->value('id');
        $seller = DB::table('roles')->where('name','seller')->value('id');
        $buyer = DB::table('roles')->where('name','buyer')->value('id');
        $all = DB::table('permissions')->pluck('id');
        DB::table('permission_role')->insert($all->map(fn($id)=>['role_id'=>$admin,'permission_id'=>$id])->all());
        $sellerNames = ['dashboard.view','products.view','products.create','products.edit','orders.view','customers.view','ai.view'];
        $sellerIds = DB::table('permissions')->whereIn('name',$sellerNames)->pluck('id');
        DB::table('permission_role')->insert($sellerIds->map(fn($id)=>['role_id'=>$seller,'permission_id'=>$id])->all());
        $buyerPermission = DB::table('permissions')->where('name','dashboard.view')->value('id');
        DB::table('permission_role')->insert(['role_id'=>$buyer,'permission_id'=>$buyerPermission]);
    }

    public function down(): void
    {
        Schema::dropIfExists('role_user');
        Schema::dropIfExists('permission_role');
        Schema::dropIfExists('permissions');
        Schema::dropIfExists('roles');
    }
};
