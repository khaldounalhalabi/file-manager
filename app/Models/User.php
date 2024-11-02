<?php

namespace App\Models;

use App\Enums\RolesPermissionEnum;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Collection as SupportCollection;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Traits\HasRoles;

/**
 * @param string                                     $first_name
 * @param string                                     $last_name
 * @param string                                     $email
 * @param Carbon                                     $email_verified_at
 * @param string                                     $fcm_token
 * @param string                                     $reset_password_code
 * @param Collection<Group>|SupportCollection<Group> $ownedGroups
 * @param Collection<Group>|SupportCollection<Group> $groups
 * @mixin Builder
 */
class User extends Authenticatable
{
    use HasFactory, Notifiable, HasRoles;

    protected $guarded = ['id'];

    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'email_verified_at',
        'fcm_token',
        'reset_password_code',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    public static function exportable(): array
    {
        return [];
    }

    protected function password(): Attribute
    {
        return Attribute::make(
            set: fn(string $value) => Hash::make($value)
        );
    }

    protected function casts(): array
    {
        return [
            'id' => 'integer',
            'email_verified_at' => 'datetime',
            'created_at' => 'datetime:Y-m-d H:i:s',
            'updated_at' => 'datetime:Y-m-d H:i:s',
        ];
    }

    public function isAdmin(): bool
    {
        return $this->hasRole(RolesPermissionEnum::ADMIN['role']);
    }

    public function isCustomer(): bool
    {
        return $this->hasRole(RolesPermissionEnum::CUSTOMER['role']);
    }

    public function groups(): BelongsToMany
    {
        return $this->belongsToMany(Group::class, 'group_users');
    }

    public function ownedGroups(): HasMany
    {
        return $this->hasMany(Group::class, 'owner_id');
    }

    /**
     * add your searchable columns, so you can search within them in the
     * index method
     */
    public static function searchableArray(): array
    {
        return [
            'first_name',
            'last_name',
            'email',
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
}
