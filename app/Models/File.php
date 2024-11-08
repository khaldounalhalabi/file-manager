<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property string       name
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
        'name',
        'status',
        'owner_id',
        'group_id',
        'directory_id',
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
public function fileVersions()
{
	 return $this->hasMany(FileVersion::class);
}


}
