<?php

namespace Database\Seeders;

use App\Models\FileLog;
use Illuminate\Database\Seeder;

class FileLogSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        FileLog::factory(10)->create();
    }
}
