<?php

namespace App\Models;

use App\Enums\FileStatusEnum;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;


/**
 * @property string       name
 * @property integer|null owner_id
 * @property integer      group_id
 * @property integer      directory_id
 * @property string       status
 * @property  User|null   owner
 * @property  Group       group
 * @property  Directory   directory
 * @property integer      frequent
 */
class File extends Model
{
    use HasFactory;
    use SoftDeletes;

    protected $fillable = [
        'name',
        'status',
        'owner_id',
        'group_id',
        'directory_id',
        'frequent',
    ];

    public function exportable(): array
    {
        return [
            'status',
            'owner_id',
            'group.name',
            'directory.name',
        ];
    }


    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function directory(): BelongsTo
    {
        return $this->belongsTo(Directory::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    /**
     * add your searchable columns, so you can search within them in the
     * index method
     */
    public static function searchableArray(): array
    {
        return [
            'name',
            'status',
        ];
    }

    public function fileVersions(): HasMany
    {
        return $this->hasMany(FileVersion::class);
    }

    public function lastVersion(): HasOne
    {
        return $this->hasOne(FileVersion::class)->ofMany('version', 'MAX');
    }

    public function isLocked(): bool
    {
        return $this->status == FileStatusEnum::LOCKED->value;
    }

    public function getFileName(): string
    {
        if ($this->frequent > 0) {
            return "$this->name ($this->frequent)." . $this->lastVersion?->file_path['extension'];
        }

        return "$this->name." . $this->lastVersion?->file_path['extension'];
    }

    public function fileExists(): bool
    {
        return file_exists($this->lastVersion?->file_path['absolute_path']);
    }
}
