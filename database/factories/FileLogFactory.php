<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\FileLog;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<FileLog>
 */
class FileLogFactory extends Factory
{

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_id' => File::factory(),
            'event_type' => fake()->word(),
            'user_id' => User::factory(),
            'happened_at' => fake()->date(),
        ];
    }
}
