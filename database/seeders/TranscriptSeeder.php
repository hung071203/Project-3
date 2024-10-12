<?php

namespace Database\Seeders;

use App\Models\Transcript;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TranscriptSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Transcript::factory('6')->create();
    }
}
