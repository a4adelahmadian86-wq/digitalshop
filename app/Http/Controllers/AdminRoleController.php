<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminRoleController extends Controller
{
    public function index(){return view('admin.roles.index',['roles'=>Role::with('permissions')->orderBy('is_system','desc')->orderBy('label')->get(),'permissions'=>Permission::orderBy('group_name')->orderBy('label')->get()]);}
    public function store(Request $request)
    {
        $data=$request->validate(['name'=>'required|string|max:80|unique:roles,name','label'=>'required|string|max:120','description'=>'nullable|string|max:255','permissions'=>'array','permissions.*'=>'exists:permissions,id']);
        $role=Role::create(['name'=>Str::slug($data['name'],'_'),'label'=>$data['label'],'description'=>$data['description']??null,'is_system'=>false]);
        $role->permissions()->sync($data['permissions']??[]);
        return back()->with('success','نقش جدید ساخته شد.');
    }
    public function update(Request $request, Role $role)
    {
        $data=$request->validate(['label'=>'required|string|max:120','description'=>'nullable|string|max:255','permissions'=>'array','permissions.*'=>'exists:permissions,id']);
        $role->update(['label'=>$data['label'],'description'=>$data['description']??null]);
        $role->permissions()->sync($data['permissions']??[]);
        return back()->with('success','دسترسی‌های نقش بروزرسانی شد.');
    }
}
