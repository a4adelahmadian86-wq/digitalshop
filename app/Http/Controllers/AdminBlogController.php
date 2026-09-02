<?php
namespace App\Http\Controllers;
use App\Models\BlogPost;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class AdminBlogController extends Controller {
 public function index(){return view('admin.blog.index',['posts'=>BlogPost::latest()->paginate(20)]);}
 public function create(){return view('admin.blog.form',['post'=>new BlogPost]);}
 public function store(Request $r){$d=$this->data($r);$d['author_id']=auth()->id();$d['published_at']=$d['is_published']?now():null;BlogPost::create($d);return redirect()->route('admin.blog.index')->with('success','مقاله ایجاد شد.');}
 public function edit(BlogPost $post){return view('admin.blog.form',compact('post'));}
 public function update(Request $r,BlogPost $post){$d=$this->data($r);$d['published_at']=$d['is_published']?($post->published_at?:now()):null;$post->update($d);return redirect()->route('admin.blog.index')->with('success','مقاله ذخیره شد.');}
 public function destroy(BlogPost $post){$post->delete();return back()->with('success','مقاله حذف شد.');}
 private function data(Request $r){$d=$r->validate(['title'=>'required|string|max:180','slug'=>'nullable|string|max:180','excerpt'=>'nullable|string|max:1000','content'=>'required|string','is_published'=>'nullable|boolean']);$d['slug']=Str::slug($d['slug']?:$d['title']);$d['is_published']=$r->boolean('is_published');return $d;}
}
