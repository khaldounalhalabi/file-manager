<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModelHasPermission extends Model
{
    protected $table = 'model_has_permissions';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['model_type', 'model_id', 'model_name', 'permissions'];
    protected $casts = [
        'permissions' => 'array'
    ];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
