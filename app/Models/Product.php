<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable=['category_id','storage_provider_id','title','slug','short_description','description','seo_keywords','meta_title','meta_description','file_format','page_count','price','file_name','file_path','storage_path','thumbnail','is_published','ai_status','ai_score','ai_summary','ai_report','ai_source_hash','ai_indexed_at','approval_status','submitted_by','submitted_at','approved_by','approved_at','approval_note'];
    protected $casts=['is_published'=>'boolean','ai_report'=>'array','ai_indexed_at'=>'datetime','submitted_at'=>'datetime','approved_at'=>'datetime','page_count'=>'integer'];
    protected $appends=['thumbnail_url'];

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) {
            if (str_starts_with($this->thumbnail,'http')) return $this->thumbnail;
            if (str_starts_with($this->thumbnail,'/')) return url($this->thumbnail);
            return asset($this->thumbnail);
        }
        $extensions=$this->relationLoaded('files')?$this->files->pluck('extension')->map(fn($x)=>strtolower((string)$x))->all():[];
        $type='file';
        foreach(['pdf'=>'pdf','doc'=>'word','docx'=>'word','xls'=>'excel','xlsx'=>'excel','csv'=>'excel','ppt'=>'powerpoint','pptx'=>'powerpoint','html'=>'html','css'=>'css','js'=>'JavaScript','py'=>'Python','php'=>'php','sql'=>'SQL','json'=>'JSON','apk'=>'APK','svg'=>'svg','wordpress'=>'WordPress'] as $extension=>$cover){if(in_array($extension,$extensions,true)){$type=$cover;break;}}
        $candidates=['Images/'.$type.'.png','Images/'.strtolower($type).'.png'];
        foreach($candidates as $path) if(is_file(base_path($path))) return url('/'.$path);
        return url('/media/cover/'.strtolower($type));
    }

    public function category():BelongsTo{return $this->belongsTo(Category::class);}
    public function storageProvider():BelongsTo{return $this->belongsTo(StorageProvider::class);}
    public function orderItems():HasMany{return $this->hasMany(OrderItem::class);}
    public function downloads():HasMany{return $this->hasMany(Download::class);}
    public function reviews():HasMany{return $this->hasMany(ProductReview::class);}
    public function aiAnalyses():HasMany{return $this->hasMany(AiProductAnalysis::class);}
    public function files():HasMany{return $this->hasMany(ProductFile::class)->orderBy('sort_order');}
    public function uploads():HasMany{return $this->hasMany(ProductUpload::class);}
    public function questions():HasMany{return $this->hasMany(ProductQuestion::class)->whereNull('parent_id');}
    public function knowledgeDocuments():HasMany{return $this->hasMany(AiProductDocument::class);}
    public function aiFeedback():HasMany{return $this->hasMany(AiFeedback::class);}
    public function orders(){return $this->belongsToMany(Order::class,'order_items')->withPivot('price');}
}
