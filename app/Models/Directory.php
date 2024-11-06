<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;


/**
 * @property string          name
 * @property integer         owner_id
 * @property integer|null    parent_id
 * @property integer         group_id
 * @property string          path
 * @property  User           owner
 * @property  Directory|null parent
 * @property  Group          group
 */
class Directory extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
        'parent_id',
        'group_id',
        'path',
    ];

    protected static function booted(): void
    {
        self::created(function (Directory $dir) {
            self::updatePath($dir);
        });

        self::updated(function (Directory $dir) {
            self::updatePath($dir);
        });
    }

    /**
     * @param Directory $dir
     * @return void
     */
    private static function updatePath(Directory $dir): void
    {
        $tree = $dir->parent_id ? ($dir->parent?->path ?? []) : [];

        $tree[] = [
            'name' => $dir->name,
            'id' => $dir->id,
        ];

        $dir->updateQuietly([
            'path' => $tree
        ]);
    }

    /**
     * Get the attributes that should be cast.
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'path' => 'array'
        ];
    }

    public function group(): BelongsTo
    {
        return $this->belongsTo(Group::class);
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Directory::class, 'parent_id');
    }

    public function subDirectories(): HasMany
    {
        return $this->hasMany(Directory::class, 'parent_id', 'id');
    }

    /**
     * add your searchable columns, so you can search within them in the
     * index method
     */
    public static function searchableArray(): array
    {
        return [
            'name',
        ];
    }

    /**
     * add your relations and their searchable columns,
     * so you can search within them in the index method
     */
    public static function relationsSearchableArray(): array
    {
        return [
        ];
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
