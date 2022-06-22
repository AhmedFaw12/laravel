<?php

namespace Database\Factories;

use App\Models\Ev;
use App\Models\SpecificationType;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Specification>
 */
class SpecificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            "body"=> $this->faker->word(),
            "model_type" =>"App\Models\Ev",
            "model_id" => Ev::get()->random()->id,
            "specification_type_id" => SpecificationType::get()->random()->id,
            "updated_by" =>  1,
        ];
    }
}
