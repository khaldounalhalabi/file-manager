<?php

namespace Database\Factories;

use App\Models\File;
use App\Models\FileVersion;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Http\UploadedFile;

/**
 * @extends Factory<FileVersion>
 */
class FileVersionFactory extends Factory
{

    /**
     * Define the model's default state.
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'file_path' => UploadedFile::fake()->image('image.png'),
            'file_id' => File::factory(),
            'version' => fake()->numberBetween(1, 2000),
        ];
    }
}
