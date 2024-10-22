<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\MorphTo;

class ModelHasRole extends Model
{
    protected $table = 'model_has_roles';
    public $timestamps = true;
    protected $guarded = ['id'];
    protected $fillable = ['model_id', 'model_type', 'role_id'];

    public function model(): MorphTo
    {
        return $this->morphTo();
    }
}
