<?php
namespace App\Models;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
class BlogPost extends Model
{
    protected $fillable=['slug','title','excerpt','content','author_id','category_id','featured_image','meta_title','meta_description','og_image','views_count','reading_time','is_published','published_at'];
    protected $casts=['is_published'=>'boolean','published_at'=>'datetime','views_count'=>'integer','reading_time'=>'integer'];
    public function author():BelongsTo{return $this->belongsTo(User::class,'author_id');}
    public function category():BelongsTo{return $this->belongsTo(BlogCategory::class,'category_id');}
    public function tags():BelongsToMany{return $this->belongsToMany(BlogTag::class,'blog_post_tag');}
}
