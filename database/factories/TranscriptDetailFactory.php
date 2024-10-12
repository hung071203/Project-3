<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\TranscriptDetail>
 */
class TranscriptDetailFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'transcript_id' => $this->faker->randomElement(DB::table('transcripts   ')->pluck('id')),
            'student_id' => $this->faker->randomElement(DB::table('students')->pluck('id')),
        'exam_type' => $this->faker->randomDigitNot('4,5,6,7,8,9'),
        'semester' => $this->faker->randomDigitNot('3,4,5,6,7,8,9'),
          'score'=> $this->faker->randomFloat($nbMaxDecimals = NULL, $min = 0, $max = 10)
        ];
    }
}
