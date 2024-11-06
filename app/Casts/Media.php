<?php

namespace App\Casts;

use App\Traits\FileHandler;
use Exception;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

class Media implements CastsAttributes
{
    use FileHandler;

    /**
     * Cast the given value.
     * @param array<string, mixed> $attributes
     */
    public function get(Model $model, string $key, mixed $value, array $attributes): mixed
    {
        if (is_string($value) || Str::isJson($value)) {
            return json_decode($value, true);
        }

        return $value;
    }

    /**
     * Prepare the given value for storage.
     * @param array<string, mixed> $attributes
     * @throws Exception
     */
    public function set(Model $model, string $key, mixed $value, array $attributes): string|null|false
    {
        $paths = $this->handleFileStoring($value, $model);
        if (is_string($paths) && !Str::isJson($paths)) {
            return json_encode($this->format($paths));
        } elseif (is_string($paths) && Str::isJson($paths)) {
            $data = json_decode($paths, true);
            foreach ($data as $key => $item) {
                $data[$key] = $this->format($item);
            }
            return json_encode($data);
        } elseif (is_array($paths)) {
            foreach ($paths as $key => $item) {
                $paths[$key] = $this->format($item);
            }
            return json_encode($paths);
        } elseif (!$paths) {
            return null;
        }
        throw new Exception("Invalid Media Value");
    }

    /**
     * @param mixed $value
     * @return array
     */
    private function format(string $value): array
    {
        return [
            'path' => asset("storage/$value"),
            'size' => file_exists(storage_path("app/public/" . trim($value, '/')))
                ? round(filesize(storage_path("app/public/" . trim($value, '/'))) / 1024)
                : 0,
            'extension' => file_exists(storage_path("app/public/" . trim($value, '/')))
                ? pathinfo(storage_path("app/public/" . trim($value, '/')), PATHINFO_EXTENSION)
                : "unknown",
            'mime_type' => file_exists(storage_path("app/public/" . trim($value, '/')))
                ? mime_content_type(storage_path("app/public/" . trim($value, '/')))
                : "unknown",
        ];
    }

    /**
     * @param UploadedFile|UploadedFile[] $value
     * @param Model                       $model
     * @return array|string|null
     */
    private function handleFileStoring(UploadedFile|array $value, Model $model): array|string|null
    {
        $images = [];

        $isArray = is_array($value);
        $files = Arr::wrap($value);

        foreach ($files as $file) {
            File::makeDirectory(storage_path('app/public/' . $model->getTable()), 0777, true, true);
            $images[] = $this->storeFile($file, $model->getTable());
        }

        return $isArray ? $images : (count($images) > 0 ? $images[0] : null);
    }
}
