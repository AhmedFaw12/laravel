<?php

namespace Database\Seeders;

use App\Models\Department;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //we will make 5 fake department
        for($i = 1; $i <= 5 ; $i++){
            $d = new Department();
            $d->name = "Department $i";
            $d->save();
        }
    }
}
