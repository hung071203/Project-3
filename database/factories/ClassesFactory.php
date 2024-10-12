<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Classes>
 */
class ClassesFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'class_name' => $this->faker->colorName,
            'grade_id' => $this->faker->randomElement(DB::table('grades')->pluck('id')),
            'school_year_id' => $this->faker->randomElement(DB::table('school_years')->pluck('id'))

        ];
    }
}
