<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property integer|null owner_id
 * @property integer      group_id
 * @property integer      directory_id
 * @property string       status
 * @property  User|null   owner
 * @property  Group       group
 * @property  Directory   directory
 */
class File extends Model
{
    use HasFactory;

    protected $fillable = [
        'owner_id',
        'group_id',
        'directory_id',
        'status',
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
            'status',
        ];
    }

    /**
     * add your relations and their searchable columns,
     * so you can search within them in the index method
     */
    public static function relationsSearchableArray(): array
    {
        return [
            'group' => [
                'name'
                //add your group desired column to be search within
            ],
            'directory' => [
                'name', 'path'
            ],
        ];
    }
}
