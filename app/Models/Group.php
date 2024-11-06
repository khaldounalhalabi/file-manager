<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Collection;


/**
 * @property string                                                                  name
 * @property integer                                                                 owner_id
 * @property  User                                                                   owner
 * @property  Collection<User>|\Illuminate\Database\Eloquent\Collection<User>|User[] users
 */
class Group extends Model
{

    use HasFactory;

    protected $fillable = [
        'name',
        'owner_id',
    ];

    /**
     * Get the attributes that should be cast.
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [];
    }

    public function exportable(): array
    {
        return [
            'name',
            'owner_id',
        ];
    }


    public function users()
    {
        return $this->belongsToMany(User::class, 'group_users');
    }

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }


    /**
     * define your columns which you want to treat them as files
     * so the base repository can store them in the storage without
     * any additional files procedures
     */
    public function filesKeys(): array
    {
        return [
            //filesKeys
        ];
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
            'users' => [
                'first_name',
                'last_name',
                'email',
            ],
            'owner' => [
                'first_name',
                'last_name',
                'email',
            ]
        ];
    }

    public function directories(): HasMany
    {
        return $this->hasMany(Directory::class);
    }

    public function files(): HasMany
    {
        return $this->hasMany(File::class);
    }
}
