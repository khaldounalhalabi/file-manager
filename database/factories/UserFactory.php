<?php

namespace Database\Factories;

use App\Enums\RolesPermissionEnum;
use App\Models\FileLog;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'first_name' => fake()->firstName(),
            'last_name' => fake()->lastName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => '12345678',
            'remember_token' => Str::random(10),
        ];
    }

    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn() => [
            'email_verified_at' => null,
        ]);
    }

    public function withGroups($count = 1): Factory|UserFactory
    {
        return $this->has(Group::factory($count));
    }

    public function customer(): Factory|UserFactory
    {
        return $this->afterCreating(function (User $user) {
            $user->assignRole(RolesPermissionEnum::CUSTOMER['role']);
        });
    }

    public function withFileLogs($count = 1): Factory|UserFactory
    {
        return $this->has(FileLog::factory($count));
    }
}
