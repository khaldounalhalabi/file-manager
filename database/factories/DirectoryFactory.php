<?php

namespace Database\Factories;

use App\Models\Directory;
use App\Models\File;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Directory>
 */
class DirectoryFactory extends Factory
{

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'name' => fake()->firstName(),
            'owner_id' => User::factory()->customer(),
            'parent_id' => null,
            'group_id' => Group::factory(),
            'path' => "",
        ];
    }

    public function withFiles($count = 1): DirectoryFactory|Factory
    {
        return $this->has(File::factory($count));
    }
}
