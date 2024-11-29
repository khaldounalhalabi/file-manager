<?php

namespace App\Services\v1\File;

use App\Enums\FileStatusEnum;
use App\Models\File;
use App\Repositories\FileRepository;
use App\Repositories\FileVersionRepository;
use App\Services\Contracts\BaseService;
use App\Services\Contracts\Makable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Str;
use ZipArchive;

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
            'owner_id' => $this->user?->id,
            'group_id' => $this->user?->group_id,
            'directory_id' => $data['directory_id'],
            'status' => FileStatusEnum::UNLOCKED->value,
            'name' => explode('.', $data['file']->getClientOriginalName())[0] ?? "Unknown file",
        ];

        $existedFile = File::where('name', $fileData['name'])
            ->where('group_id', $fileData['group_id'])
            ->where('directory_id', $fileData['directory_id'])
            ->latest()
            ->first();

        if ($existedFile) {
            $fileData['frequent'] = $existedFile->frequent + 1;
        }

        $file = $this->repository->create($fileData);

        FileVersionRepository::make()->create([
            'file_path' => $data['file'],
            'file_id' => $file->id,
            'version' => 1
        ]);

        return $file->load($relationships);
    }

    public function edit($fileId): ?string
    {
        $file = $this->repository->find($fileId, ['lastVersion']);

        if (!$file) {
            return null;
        }

        if ($file->isLocked()) {
            return null;
        }

        $file = $this->repository->update([
            'status' => FileStatusEnum::LOCKED->value,
        ], $file);

        return $file?->lastVersion?->file_path['path'];
    }

    /**
     * @param array{file:UploadedFile , file_id:numeric} $data
     * @return bool|null
     */
    public function pushUpdates(array $data): ?bool
    {
        $file = $this->repository->find($data['file_id'], ['lastVersion']);

        //TODO:: add locker checking via logs after adding them
        if (!$file->isLocked()) {
            return false;
        }

        if ($file->getFileName() != $data['file']?->getClientOriginalName()) {
            return false;
        }

        FileVersionRepository::make()->create([
            'file_path' => $data['file'],
            'version' => ($file->lastVersion?->version ?? 0) + 1,
            'file_id' => $file->id,
        ]);

        $this->repository->update([
            'status' => FileStatusEnum::UNLOCKED->value,
        ], $file);

        return true;
    }

    public function delete($id): ?bool
    {
        $file = $this->repository->find($id);
        if (!$file) {
            return null;
        }

        if ($file->isLocked()) {
            return false;
        }

        return $this->repository->delete($file);
    }

    public function zipMultipleFiles(array $data): ?string
    {
        $files = $this->repository->getByIds($data['files_ids'], ['lastVersion']);
        $zipFileName = Str::uuid() . '.zip';
        $zipFilePath = storage_path('app/public/' . $zipFileName);

        $zip = new ZipArchive();

        if ($zip->open($zipFilePath, ZipArchive::CREATE | ZipArchive::OVERWRITE) === true) {
            foreach ($files as $file) {
                if (!$file->isLocked() && $file->lastVersion) {
                    $zip->addFile($file->lastVersion?->file_path['absolute_path'], $file->getFileName());
                    $file->update([
                        'status' => FileStatusEnum::LOCKED->value,
                    ]);
                }
            }
            $zip->close();
        } else {
            return null;
        }
        return asset("storage/" . Str::after($zipFilePath, "storage\app/public"));
    }
}
