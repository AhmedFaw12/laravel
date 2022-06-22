<?php

namespace Database\Factories;

use App\Models\EvManufacturer;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\EvModel>
 */
class EvModelFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'name' => $this->faker->word(),
            'ev_manufacturer_id' => EvManufacturer::get()->random()->id,
            'updated_by' =>1,
        ];
    }
}
