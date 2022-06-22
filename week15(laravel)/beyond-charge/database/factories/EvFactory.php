<?php

namespace Database\Factories;

use App\Models\User;
use App\Models\EvModel;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Ev>
` */
class EvFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "battery_capacity" =>$this->faker->numberBetween([1000,1500]),
            'ev_model_id' => EvModel::get()->random()->id,
            'user_id' => User::get()->random()->id,
        ];
    }
}
