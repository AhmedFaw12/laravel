<?php

namespace Database\Seeders;

use App\Models\Ev;
use App\Models\User;
use App\Models\EvModel;
use App\Models\Specification;
use App\Models\EvManufacturer;
use Illuminate\Database\Seeder;
use App\Models\SpecificationType;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        $this->call([
            UserSeeder::class,
        ]);
        User::factory(10)->create();
        EvManufacturer::factory(10)->create();
        EvModel::factory(50)->create();
        Ev::factory(150)->create();
        SpecificationType::factory(5)->create();
        Specification::factory(500)->create();
    }
}
