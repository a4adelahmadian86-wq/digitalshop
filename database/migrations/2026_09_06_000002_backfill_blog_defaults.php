<?php
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;
return new class extends Migration
{
 public function up():void{$cat=DB::table('blog_categories')->first();if(!$cat){$id=DB::table('blog_categories')->insertGetId(['name'=>'عمومی','slug'=>'general','description'=>'مطالب عمومی و آموزشی فایل‌مارکت','is_active'=>1,'created_at'=>now(),'updated_at'=>now()]);}else{$id=$cat->id;}$posts=DB::table('blog_posts')->whereNull('category_id')->get(['id']);foreach($posts as $p)DB::table('blog_posts')->where('id',$p->id)->update(['category_id'=>$id]);if(Schema::hasColumn('blog_posts','featured_image')){$i=1;foreach(DB::table('blog_posts')->whereNull('featured_image')->orderBy('id')->get(['id']) as $p){$path='Images/Banners/'.(($i-1)%4+1).'.png';if(is_file(base_path($path)))DB::table('blog_posts')->where('id',$p->id)->update(['featured_image'=>'/'.$path,'og_image'=>'/'.$path]);$i++;}}}
 public function down():void{}
};
