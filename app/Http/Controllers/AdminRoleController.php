<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRoleController extends Controller
{
    public function index()
    {
        return view('admin.roles.index',[
            'roles'=>Role::with('permissions')->orderBy('is_system','desc')->orderBy('label')->get(),
            'permissions'=>Permission::orderBy('group_name')->orderBy('label')->get(),
            'widgets'=>[
                'summary'=>'خلاصه عملکرد','stats'=>'کارت‌های آماری','attention'=>'موارد نیازمند توجه',
                'recent_orders'=>'آخرین سفارش‌ها','wallet'=>'کیف پول','quick_links'=>'دسترسی سریع','role_tools'=>'ابزارهای نقش',
            ],
        ]);
    }

    public function store(Request $request)
    {
        $data=$request->validate([
            'name'=>'required|string|max:80|unique:roles,name','label'=>'required|string|max:120','description'=>'nullable|string|max:255',
            'permissions'=>'array','permissions.*'=>'exists:permissions,id','dashboard_widgets'=>'array','dashboard_widgets.*'=>'string',
        ]);
        $role=Role::create(['name'=>Str::slug($data['name'],'_'),'label'=>$data['label'],'description'=>$data['description']??null,'is_system'=>false,'dashboard_widgets'=>$data['dashboard_widgets']??null]);
        $role->permissions()->sync($data['permissions']??[]);
        return back()->with('success','نقش جدید ساخته شد.');
    }

    public function update(Request $request, Role $role)
    {
        $data=$request->validate([
            'label'=>'required|string|max:120','description'=>'nullable|string|max:255','permissions'=>'array','permissions.*'=>'exists:permissions,id',
            'dashboard_widgets'=>'array','dashboard_widgets.*'=>'string',
        ]);
        $role->update(['label'=>$data['label'],'description'=>$data['description']??null,'dashboard_widgets'=>$data['dashboard_widgets']??[]]);
        $role->permissions()->sync($data['permissions']??[]);
        return back()->with('success','دسترسی‌ها و چیدمان پیشخوان نقش بروزرسانی شد.');
    }
}
