<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\DB;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Transcript>
 */
class TranscriptFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transcript_name' => $this->faker->sentence,
            'division_id' => $this->faker->randomElement(DB::table('divisions')->pluck('id')),
            'class_id' => $this->faker->randomElement(DB::table('classes')->pluck('id'))
        ];
    }
}
