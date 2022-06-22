<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Specification extends Model
{
    use HasFactory;

    function specification_type(){
        return $this->belongsTo(SpecificationType::class);
    }

    function model(){
        return $this->morphTo();
    }
}
