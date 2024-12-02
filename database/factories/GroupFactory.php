<?php

namespace Database\Factories;

use App\Models\Directory;
use App\Models\File;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<\App\Models\Group>
 */
class GroupFactory extends Factory
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

        ];
    }

    public function withUsers($count = 1): GroupFactory|Factory
    {
        return $this->has(User::factory($count)->customer());
    }

    public function withDirectories($count = 1): GroupFactory|Factory
    {
        return $this->has(Directory::factory($count));
    }

    public function withFiles($count = 1): GroupFactory|Factory
    {
        return $this->has(File::factory($count));
    }
}
