<?php
namespace App\Http\Controllers;
use App\Models\BlogCategory;
use App\Models\BlogPost;
use App\Models\BlogTag;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
class AdminBlogController extends Controller
{
 public function index(){return view('admin.blog.index',['posts'=>BlogPost::with(['category','author'])->latest()->paginate(20)]);}
 public function create(){return view('admin.blog.form',['post'=>new BlogPost,'categories'=>BlogCategory::where('is_active',true)->orderBy('name')->get()]);}
 public function store(Request $r){$d=$this->data($r);$d['author_id']=auth()->id();$d['published_at']=$d['is_published']?now():null;$post=BlogPost::create($d);$this->syncRelations($post,$r);return redirect()->route('admin.blog.index')->with('success','مقاله ایجاد شد.');}
 public function edit(BlogPost $post){$post->load('tags');return view('admin.blog.form',['post'=>$post,'categories'=>BlogCategory::where('is_active',true)->orderBy('name')->get()]);}
 public function update(Request $r,BlogPost $post){$d=$this->data($r);$d['published_at']=$d['is_published']?($post->published_at?:now()):null;$post->update($d);$this->syncRelations($post,$r);return redirect()->route('admin.blog.index')->with('success','مقاله ذخیره شد.');}
 public function destroy(BlogPost $post){$post->delete();return back()->with('success','مقاله حذف شد.');}
 private function data(Request $r){$d=$r->validate(['title'=>'required|string|max:180','slug'=>'nullable|string|max:180','excerpt'=>'nullable|string|max:1000','content'=>'required|string','category'=>'nullable|string|max:120','tags'=>'nullable|string|max:2000','meta_title'=>'nullable|string|max:255','meta_description'=>'nullable|string|max:320','featured_image'=>'nullable|image|max:5120','is_published'=>'nullable|boolean']);$d['slug']=Str::slug($d['slug']?:$d['title']);$d['is_published']=$r->boolean('is_published');unset($d['category'],$d['tags']);if($r->hasFile('featured_image')){$dir=public_path('blog');if(!is_dir($dir))@mkdir($dir,0775,true);$file=$r->file('featured_image');$name=Str::slug(pathinfo($file->getClientOriginalName(),PATHINFO_FILENAME)).'-'.Str::random(8).'.'.$file->getClientOriginalExtension();$file->move($dir,$name);$d['featured_image']='/blog/'.$name;$d['og_image']='/blog/'.$name;}return $d;}
 private function syncRelations(BlogPost $post,Request $r):void{$category=trim((string)$r->input('category',''));if($category!==''){$cat=BlogCategory::firstOrCreate(['slug'=>Str::slug($category)],['name'=>$category,'is_active'=>true]);$post->update(['category_id'=>$cat->id]);}else{$post->update(['category_id'=>null]);}$tags=[];foreach(preg_split('/[,،\n]+/u',(string)$r->input('tags',''))?:[] as $name){$name=trim($name);if($name==='')continue;$tag=BlogTag::firstOrCreate(['slug'=>Str::slug($name)],['name'=>$name]);$tags[]=$tag->id;}$post->tags()->sync($tags);}
}
