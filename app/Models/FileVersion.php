<?php

namespace App\Models;

use App\Casts\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property string  file_path
 * @property integer file_id
 * @property numeric version
 * @property  File   file
 */
class FileVersion extends Model
{

    use HasFactory;

    protected $fillable = [
        'file_path',
        'file_id',
        'version',
    ];

    protected function casts(): array
    {
        return [
            'file_path' => Media::class,
        ];
    }

    public function exportable(): array
    {
        return [
            'file_path',
            'version',
            'file.id',
        ];
    }

    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    /**
     * add your searchable columns, so you can search within them in the
     * index method
     */
    public static function searchableArray(): array
    {
        return [
            'file_path',
        ];
    }

    /**
     * add your relations and their searchable columns,
     * so you can search within them in the index method
     */
    public static function relationsSearchableArray(): array
    {
        return [
            'file' => [
                'status',
                'name',
            ],
        ];
    }
}
