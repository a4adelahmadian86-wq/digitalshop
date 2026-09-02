<?php
namespace App\Http\Controllers;
use App\Models\SitePage;
use Illuminate\Http\Request;
class AdminSiteContentController extends Controller {
 public function index(){return view('admin.content.index',['pages'=>SitePage::orderBy('title')->get()]);}
 public function edit(SitePage $page){return view('admin.content.edit',compact('page'));}
 public function update(Request $request,SitePage $page){$data=$request->validate(['title'=>'required|string|max:180','meta_title'=>'nullable|string|max:180','meta_description'=>'nullable|string|max:500','content'=>'required|string','is_published'=>'nullable|boolean']);$page->update($data);return redirect()->route('admin.content.index')->with('success','صفحه ذخیره شد.');}
}
