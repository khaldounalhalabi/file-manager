<?php

namespace App\Services\v1\File;

use App\Enums\FileStatusEnum;
use App\Models\File;
use App\Repositories\FileRepository;
use App\Repositories\FileVersionRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;
use Illuminate\Database\Eloquent\Model;

/**
 * @extends BaseService<File>
 * @property FileRepository $repository
 */
class FileService extends BaseService
{
    use Makable;

    protected string $repositoryClass = FileRepository::class;

    public function store(array $data, array $relationships = []): Model
    {
        $fileData = [
            'owner_id' => auth()->user()?->id,
            'group_id' => auth()->user()?->group_id,
            'directory_id' => $data['directory_id'],
            'status' => FileStatusEnum::UNLOCKED->value,
            'name' => $data['file']->getClientOriginalName(),
        ];

        $file = $this->repository->create($fileData);

        FileVersionRepository::make()->create([
            'file_path' => $data['file'],
            'file_id' => $file->id,
            'version' => 0
        ]);

        return $file->load($relationships);
    }
}
