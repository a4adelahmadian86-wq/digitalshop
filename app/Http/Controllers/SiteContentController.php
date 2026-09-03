<?php
namespace App\Http\Controllers;
use App\Models\SitePage;
use App\Models\BlogPost;
use Illuminate\Http\Request;
class SiteContentController extends Controller {
 public function page(string $slug){$page=SitePage::where('slug',$slug)->where('is_published',true)->firstOrFail();return view('pages.content',compact('page'));}
 public function blog(){ $posts=BlogPost::where('is_published',true)->latest('published_at')->latest()->paginate(9); return view('blog.index',compact('posts')); }
 public function post(string $slug){$post=BlogPost::where('slug',$slug)->where('is_published',true)->firstOrFail();return view('blog.show',compact('post'));}
}
