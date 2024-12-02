<?php

namespace App\Repositories;

use App\Enums\FileLogTypeEnum;
use App\Excel\LogExporter;
use App\Models\File;
use App\Models\FileLog;
use App\Models\User;
use App\Repositories\Contracts\BaseRepository;
use Illuminate\Database\Eloquent\Builder;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

/**
 * @extends  BaseRepository<FileLog>
 */
class FileLogRepository extends BaseRepository
{
    protected string $modelClass = FileLog::class;

    public function getByFile($fileId, array $relations = []): ?array
    {
        return $this->paginate(
            $this->globalQuery($relations)
                ->where('file_id', $fileId)
                ->orderBy('happened_at', 'desc')
        );
    }

    /**
     * @param array $ids
     * @return BinaryFileResponse
     */
    public function export(array $ids = []): BinaryFileResponse
    {
        $fileId = request()->route('fileId');
        if (!$fileId) {
            $collection = collect();
        } else {
            $collection = $this->globalQuery()->where('file_id', $fileId)
                ->whereHas('user', function (User|Builder $query) {
                    $query->where('group_id', auth()->user()->group_id);
                })->get();
        }

        $requestedColumns = request("columns") ?? null;
        return Excel::download(
            new LogExporter($collection, $this->model, $requestedColumns),
            $this->model->getTable() . ".xlsx",
        );
    }

    public function getLatestByEvent(FileLogTypeEnum $eventType, int $fileId)
    {
        return $this->globalQuery()
            ->where('file_id', $fileId)
            ->where('event_type', $eventType->value)
            ->orderBy('happened_at', 'desc')
            ->first();
    }

    public function logEvent(FileLogTypeEnum $eventType, File $file)
    {
        return FileLog::create([
            'file_id' => $file->id,
            'user_id' => auth()->user()->id,
            'happened_at' => now(),
            'event_type' => $eventType->value,
        ]);
    }
}
