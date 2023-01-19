<?php

namespace App\Models\Authorization;

use App\Models\Admin;
use App\Models\User;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Spatie\Permission\Models\Permission as PermissionModel;

/**
 * App\Models\Authorization\Permission.
 *
 * @property int                                                                       $id
 * @property string                                                                    $uuid
 * @property string                                                                    $name
 * @property string                                                                    $description
 * @property string                                                                    $guard_name
 * @property \Illuminate\Support\Carbon|null                                           $created_at
 * @property \Illuminate\Support\Carbon|null                                           $updated_at
 * @property \Illuminate\Database\Eloquent\Collection|Admin[]                          $admins
 * @property int|null                                                                  $admins_count
 * @property \Illuminate\Database\Eloquent\Collection|Permission[]                     $permissions
 * @property int|null                                                                  $permissions_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Authorization\Role[] $roles
 * @property int|null                                                                  $roles_count
 * @property \Illuminate\Database\Eloquent\Collection|User[]                           $users
 * @property int|null                                                                  $users_count
 *
 * @method static \Database\Factories\Authorization\PermissionFactory factory(...$parameters)
 * @method static Builder|Permission                                  newModelQuery()
 * @method static Builder|Permission                                  newQuery()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission    permission($permissions)
 * @method static Builder|Permission                                  query()
 * @method static \Illuminate\Database\Eloquent\Builder|Permission    role($roles, $guard = null)
 * @method static Builder|Permission                                  search(string $search)
 * @method static Builder|Permission                                  whereCreatedAt($value)
 * @method static Builder|Permission                                  whereDescription($value)
 * @method static Builder|Permission                                  whereGuardName($value)
 * @method static Builder|Permission                                  whereId($value)
 * @method static Builder|Permission                                  whereName($value)
 * @method static Builder|Permission                                  whereUpdatedAt($value)
 * @method static Builder|Permission                                  whereUuid($value)
 *
 * @mixin \Eloquent
 */
class Permission extends PermissionModel
{
    use HasFactory;

    // Import my own traits
    use HasUuid;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'permissions';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'guard_name',
        'description',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<string, string>
     */
    protected $dates = [];

    /**
     * A permission belongs to some admins of the model associated with its guard.
     */
    public function admins(): BelongsToMany
    {
        return $this->morphedByMany(
            Admin::class,
            'model',
            'model_has_permissions',
            'permission_id',
            'model_id'
        );
    }

    /**
     * A permission can be applied to roles.
     */
    public function roles(): BelongsToMany
    {
        return $this->belongsToMany(
            Role::class,
            'role_has_permissions',
            'permission_id',
            'role_id'
        );
    }

    /**
     * A permission belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            User::class,
            'model',
            'model_has_permissions',
            'permission_id',
            'model_id'
        );
    }

    /**
     * Scope a query with search.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('description', 'like', '%'.$search.'%');
    }
}
