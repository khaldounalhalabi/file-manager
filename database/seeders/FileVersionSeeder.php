<?php

namespace Database\Seeders;

use App\Models\FileVersion;
use Illuminate\Database\Seeder;

class FileVersionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FileVersion::factory(10)->create();
    }
}
