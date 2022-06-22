<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {//we want to make super admin
        $user = new User();
        $user->first_name = "admin";
        $user->last_name = "super";
        // $user->password = Hash::make("123456789");
        $user->password = bcrypt("123456789");
        $user->email = "ahmed@gmail.com";
        $user->mobile = "01006654568";
        $user->save();
    }
}
