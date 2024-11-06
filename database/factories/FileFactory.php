<?php

namespace Database\Factories;

use App\Models\Directory;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\File>
 */
class FileFactory extends Factory
{

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'owner_id' => User::factory(),
            'group_id' => Group::factory(),
            'directory_id' => Directory::factory(),
        ];
    }
}
