<?php

namespace Database\Seeders;

use App\Models\Cours;
use Illuminate\Database\Seeder;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CoursSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Cours::factory(5)->create();
    }
}
