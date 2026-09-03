<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class AiModelExperiment extends Model
{
    protected $fillable=['name','provider','model','task','weight','enabled','config','metrics','started_at','ended_at'];
    protected $casts=['enabled'=>'boolean','config'=>'array','metrics'=>'array','started_at'=>'datetime','ended_at'=>'datetime','weight'=>'decimal:2'];
}
