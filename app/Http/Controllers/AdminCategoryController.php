<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AdminCategoryController extends Controller
{
    public function index(){ $categories=Category::withCount('products')->with('parent')->orderBy('level')->orderBy('sort_order')->orderBy('name')->get(); return view('admin.categories.index',compact('categories')); }
    public function create(){ $parents=Category::whereNull('parent_id')->where('is_active',true)->where('status',true)->orderBy('sort_order')->orderBy('name')->get(); return view('admin.categories.create',compact('parents')); }
    public function store(Request $request){ $data=$this->validateData($request);$parent=$data['parent_id']?Category::findOrFail($data['parent_id']):null;Category::create(['parent_id'=>$parent?->id,'name'=>$data['name'],'slug'=>$data['slug'],'level'=>$parent?1:0,'description'=>$data['description']??null,'image'=>$data['image']??null,'is_active'=>$request->boolean('is_active',true),'status'=>$request->boolean('is_active',true),'sort_order'=>$data['sort_order']??0]);return redirect()->route('admin.categories.index')->with('success','دسته‌بندی با موفقیت ایجاد شد.'); }
    public function edit(Category $category){ $parents=Category::whereNull('parent_id')->where('id','<>',$category->id)->where('is_active',true)->where('status',true)->orderBy('sort_order')->orderBy('name')->get();return view('admin.categories.edit',compact('category','parents')); }
    public function update(Request $request,Category $category){$data=$this->validateData($request,$category);$parent=$data['parent_id']?Category::findOrFail($data['parent_id']):null;$active=$request->boolean('is_active');$category->update(['parent_id'=>$parent?->id,'name'=>$data['name'],'slug'=>$data['slug'],'level'=>$parent?1:0,'description'=>$data['description']??null,'image'=>$data['image']??null,'is_active'=>$active,'status'=>$active,'sort_order'=>$data['sort_order']??0]);return redirect()->route('admin.categories.index')->with('success','دسته‌بندی با موفقیت ویرایش شد.');}
    public function toggle(Category $category){$active=!$category->is_active;$category->update(['is_active'=>$active,'status'=>$active]);return back()->with('success',$active?'دسته‌بندی فعال شد.':'دسته‌بندی غیرفعال شد.');}
    public function destroy(Category $category){if($category->products()->exists()||$category->children()->exists())return back()->with('error','این دسته‌بندی دارای محصول یا زیرگروه است و قابل حذف نیست. ابتدا وابستگی‌ها را جابه‌جا کنید.');$category->delete();return back()->with('success','دسته‌بندی با موفقیت حذف شد.');}
    private function validateData(Request $request,?Category $category=null):array{$unique='unique:categories,slug'.($category?','.$category->id:'');return $request->validate(['parent_id'=>['nullable','integer','exists:categories,id'], 'name'=>['required','string','max:255'],'slug'=>['required','string','max:255','regex:/^[A-Za-z0-9_-]+$/',$unique],'description'=>['nullable','string'],'image'=>['nullable','string','max:2048'],'sort_order'=>['nullable','integer','min:0']]);}
}
