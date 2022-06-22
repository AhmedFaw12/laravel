<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ev extends Model
{
    use HasFactory;
    protected $table = "evs";
    protected $guarded = [];

    function user(){
        return $this->belongsTo(User::class);
    }
    function ev_model(){
        return $this->belongsTo(EvModel::class);
    }

    function specifications(){
        return  $this->morphMany(Specification::class, "model");
    }
}
