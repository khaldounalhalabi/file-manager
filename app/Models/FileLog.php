<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;


/**
 * @property integer file_id
 * @property string  event_type
 * @property integer user_id
 * @property string  happened_at
 * @property  File   file
 * @property  User   user
 */
class FileLog extends Model
{

    use HasFactory;

    protected $fillable = [
        'file_id',
        'event_type',
        'user_id',
        'happened_at',
    ];

    /**
     * Get the attributes that should be cast.
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'happened_at' => 'datetime'
        ];
    }

    public function exportable(): array
    {
        return [
            'event_type',
            'happened_at',
            'user.first_name',
            'user.last_name',
            'user.email',
            'file.name',
        ];
    }


    public function file(): BelongsTo
    {
        return $this->belongsTo(File::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
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
            'event_type',

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
                'status'
                //add your file desired column to be search within
            ],
            'user' => [
                'first_name', 'last_name', 'email', 'password'
                //add your user desired column to be search within
            ],

        ];
    }


}
