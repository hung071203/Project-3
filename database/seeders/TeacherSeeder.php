<?php

namespace Database\Seeders;

use App\Models\Teacher;
use Database\Factories\TeacherFactory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class TeacherSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Teacher::factory('15')->create();
    }
}
