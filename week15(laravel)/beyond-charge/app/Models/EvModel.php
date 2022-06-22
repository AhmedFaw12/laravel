<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EvModel extends Model
{
    use HasFactory;

    function evs(){
        return $this->hasMany(Ev::class);
    }

    function ev_manufacturer(){
        return $this->belongsTo(EvManufacturer::class);
    }
}
