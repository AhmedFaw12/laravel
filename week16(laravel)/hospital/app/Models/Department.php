<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;

    //belongsTo relation
    function main_department(){
        return $this->belongsTo(Department::class, "department_id", "id");
    }

    //hasMany relation
    function sections(){
        return $this->hasMany(Department::class, "department_id", "id");
    }

}
