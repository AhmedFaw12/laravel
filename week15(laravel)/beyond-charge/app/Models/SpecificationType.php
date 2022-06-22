<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SpecificationType extends Model
{
    use HasFactory;

    function specifications(){//function named after table name plural
        return $this->hasMany(Specification::class);
    }
}
