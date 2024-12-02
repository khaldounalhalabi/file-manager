<?php

namespace Database\Factories;

use App\Models\Directory;
use App\Models\FileLog;
use App\Models\FileVersion;
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

    public function withFileVersions($count = 1): FileFactory|Factory
    {
        return $this->has(FileVersion::factory($count));
    }

    public function withFileLogs($count = 1): FileFactory|Factory
    {
        return $this->has(FileLog::factory($count));
    }
}
