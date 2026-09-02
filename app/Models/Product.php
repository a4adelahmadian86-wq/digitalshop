<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Product extends Model
{
    protected $fillable=['category_id','storage_provider_id','title','slug','short_description','description','price','file_name','file_path','storage_path','thumbnail','is_published','ai_status','ai_score','ai_summary','ai_report','ai_source_hash','ai_indexed_at','approval_status','submitted_by','approved_by','approved_at','approval_note'];
    protected $casts=['is_published'=>'boolean','ai_report'=>'array','ai_indexed_at'=>'datetime','approved_at'=>'datetime'];
    protected $appends=['thumbnail_url'];

    public function getThumbnailUrlAttribute(): string
    {
        if ($this->thumbnail) return str_starts_with($this->thumbnail, 'http') ? $this->thumbnail : asset($this->thumbnail);
        $extensions=$this->relationLoaded('files')?$this->files->pluck('extension')->map(fn($e)=>strtolower((string)$e))->all():[];
        if(in_array('pdf',$extensions,true))return asset('Images/pdf.png');
        if(array_intersect(['doc','docx'],$extensions))return asset('Images/word.png');
        if(array_intersect(['xls','xlsx','csv'],$extensions))return asset('Images/excel.png');
        if(array_intersect(['ppt','pptx'],$extensions))return asset('Images/powerpoint.png');
        if(in_array('html',$extensions,true))return asset('Images/html.png');
        if(in_array('css',$extensions,true))return asset('Images/css.png');
        if(in_array('js',$extensions,true))return asset('Images/JavaScript.png');
        if(in_array('py',$extensions,true))return asset('Images/Python.png');
        if(in_array('php',$extensions,true))return asset('Images/php.png');
        if(in_array('sql',$extensions,true))return asset('Images/SQL.png');
        if(in_array('json',$extensions,true))return asset('Images/JSON.png');
        if(in_array('apk',$extensions,true))return asset('Images/APK.png');
        if(in_array('svg',$extensions,true))return asset('Images/svg.png');
        if(in_array('wordpress',$extensions,true))return asset('Images/WordPress.png');
        return asset('Images/pdf.png');
    }

    public function category(): BelongsTo{return $this->belongsTo(Category::class);}
    public function storageProvider(): BelongsTo{return $this->belongsTo(StorageProvider::class);}
    public function orderItems(): HasMany{return $this->hasMany(OrderItem::class);}
    public function downloads(): HasMany{return $this->hasMany(Download::class);}
    public function reviews(): HasMany{return $this->hasMany(ProductReview::class);}
    public function aiAnalyses(): HasMany{return $this->hasMany(AiProductAnalysis::class);}
    public function files(): HasMany{return $this->hasMany(ProductFile::class)->orderBy('sort_order');}
    public function uploads(): HasMany{return $this->hasMany(ProductUpload::class);}
    public function questions(): HasMany{return $this->hasMany(ProductQuestion::class)->whereNull('parent_id');}
    public function knowledgeDocuments(): HasMany{return $this->hasMany(AiProductDocument::class);}
    public function aiFeedback(): HasMany{return $this->hasMany(AiFeedback::class);}
    public function orders(){return $this->belongsToMany(Order::class,'order_items')->withPivot('price');}
}
