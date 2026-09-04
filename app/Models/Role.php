<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Role extends Model
{
    protected $fillable=['name','label','description','is_system','dashboard_widgets'];
    protected $casts=['is_system'=>'boolean','dashboard_widgets'=>'array'];
    public function permissions(): BelongsToMany{return $this->belongsToMany(Permission::class);}
    public function users(): BelongsToMany{return $this->belongsToMany(User::class);}

    public function dashboardHas(string $key): bool
    {
        $widgets=$this->dashboard_widgets;
        if ($widgets===null) return true;
        return in_array($key,$widgets,true);
    }
}
