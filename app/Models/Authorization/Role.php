<?php

namespace App\Models\Authorization;

use App\Models\Admin;
use App\Models\User;
use App\Traits\HasRelated;
use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Models\Role as RoleModel;

/**
 * App\Models\Authorization\Role.
 *
 * @property int                                                                             $id
 * @property string                                                                          $uuid
 * @property string                                                                          $name
 * @property string                                                                          $description
 * @property string                                                                          $guard_name
 * @property \Illuminate\Support\Carbon|null                                                 $created_at
 * @property \Illuminate\Support\Carbon|null                                                 $updated_at
 * @property \Illuminate\Support\Carbon|null                                                 $deleted_at
 * @property \Illuminate\Database\Eloquent\Collection|Admin[]                                $admins
 * @property int|null                                                                        $admins_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Authorization\Permission[] $permissions
 * @property int|null                                                                        $permissions_count
 * @property \Illuminate\Database\Eloquent\Collection|User[]                                 $users
 * @property int|null                                                                        $users_count
 *
 * @method static \Database\Factories\Authorization\RoleFactory factory(...$parameters)
 * @method static Builder|Role                                  newModelQuery()
 * @method static Builder|Role                                  newQuery()
 * @method static \Illuminate\Database\Query\Builder|Role       onlyTrashed()
 * @method static \Illuminate\Database\Eloquent\Builder|Role    permission($permissions)
 * @method static Builder|Role                                  query()
 * @method static Builder|Role                                  search(string $search)
 * @method static Builder|Role                                  whereCreatedAt($value)
 * @method static Builder|Role                                  whereDeletedAt($value)
 * @method static Builder|Role                                  whereDescription($value)
 * @method static Builder|Role                                  whereGuardName($value)
 * @method static Builder|Role                                  whereId($value)
 * @method static Builder|Role                                  whereName($value)
 * @method static Builder|Role                                  whereUpdatedAt($value)
 * @method static Builder|Role                                  whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Role       withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Role       withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Role extends RoleModel
{
    use HasFactory;
    use SoftDeletes;

    // Import my own traits
    use HasUuid;
    use HasRelated;

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'roles';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
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
     * @var array<int, string>
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * The related model.
     *
     * @var array<int, string>
     */
    public $relateds = ['users'];

    /**
     * A role belongs to some admins of the model associated with its guard.
     */
    public function admins(): BelongsToMany
    {
        return $this->morphedByMany(
            Admin::class,
            'model',
            'model_has_roles',
            'role_id',
            'model_id'
        );
    }

    /**
     * A role may be given various permissions.
     */
    public function permissions(): BelongsToMany
    {
        return $this->belongsToMany(
            Permission::class,
            'role_has_permissions',
            'role_id',
            'permission_id'
        );
    }

    /**
     * A role belongs to some users of the model associated with its guard.
     */
    public function users(): BelongsToMany
    {
        return $this->morphedByMany(
            User::class,
            'model',
            'model_has_roles',
            'role_id',
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
