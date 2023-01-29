<?php

namespace App\Models;

use App\Traits\HasUuid;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Cashier\Billable;
use Laravel\Sanctum\HasApiTokens;
use Spatie\Permission\Traits\HasRoles;

/**
 * App\Models\Admin.
 *
 * @property int                                                                                                       $id
 * @property string                                                                                                    $uuid
 * @property string|null                                                                                               $avatar
 * @property string                                                                                                    $name
 * @property string|null                                                                                               $lastname
 * @property string                                                                                                    $email
 * @property \Illuminate\Support\Carbon|null                                                                           $email_verified_at
 * @property string                                                                                                    $password
 * @property string|null                                                                                               $remember_token
 * @property bool                                                                                                      $disabled
 * @property \Illuminate\Support\Carbon|null                                                                           $created_at
 * @property \Illuminate\Support\Carbon|null                                                                           $updated_at
 * @property \Illuminate\Support\Carbon|null                                                                           $deleted_at
 * @property \Illuminate\Notifications\DatabaseNotificationCollection|\Illuminate\Notifications\DatabaseNotification[] $notifications
 * @property int|null                                                                                                  $notifications_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Authorization\Permission[]                           $permissions
 * @property int|null                                                                                                  $permissions_count
 * @property \Illuminate\Database\Eloquent\Collection|\App\Models\Authorization\Role[]                                 $roles
 * @property int|null                                                                                                  $roles_count
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Cashier\Subscription[]                                  $subscriptions
 * @property int|null                                                                                                  $subscriptions_count
 * @property \Illuminate\Database\Eloquent\Collection|\Laravel\Sanctum\PersonalAccessToken[]                           $tokens
 * @property int|null                                                                                                  $tokens_count
 *
 * @method static Builder|Admin                            newModelQuery()
 * @method static Builder|Admin                            newQuery()
 * @method static \Illuminate\Database\Query\Builder|Admin onlyTrashed()
 * @method static Builder|Admin                            permission($permissions)
 * @method static Builder|Admin                            query()
 * @method static Builder|Admin                            role($roles, $guard = null)
 * @method static Builder|Admin                            search(string $search)
 * @method static Builder|Admin                            whereAvatar($value)
 * @method static Builder|Admin                            whereCreatedAt($value)
 * @method static Builder|Admin                            whereDeletedAt($value)
 * @method static Builder|Admin                            whereDisabled($value)
 * @method static Builder|Admin                            whereEmail($value)
 * @method static Builder|Admin                            whereEmailVerifiedAt($value)
 * @method static Builder|Admin                            whereId($value)
 * @method static Builder|Admin                            whereLastname($value)
 * @method static Builder|Admin                            whereName($value)
 * @method static Builder|Admin                            wherePassword($value)
 * @method static Builder|Admin                            whereRememberToken($value)
 * @method static Builder|Admin                            whereUpdatedAt($value)
 * @method static Builder|Admin                            whereUuid($value)
 * @method static \Illuminate\Database\Query\Builder|Admin withTrashed()
 * @method static \Illuminate\Database\Query\Builder|Admin withoutTrashed()
 *
 * @mixin \Eloquent
 */
class Admin extends Authenticatable
{
    use Billable;
    use HasApiTokens;
    use HasFactory;
    use HasRoles;
    use Notifiable;
    use SoftDeletes;

    // Import my own traits
    use HasUuid;

    /**
     * The guard that access on authentication.
     *
     * @var string
     */
    protected $guard_name = 'web_admin';

    /**
     * The table associated with the model.
     *
     * @var string
     */
    protected $table = 'admins';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'uuid',
        'avatar',
        'name',
        'lastname',
        'email',
        'password',
        'disabled',
    ];

    /**
     * The attributes that aren't mass assignable.
     *
     * @var array<int, string>
     */
    protected $guarded = [];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'disabled' => 'boolean',
    ];

    /**
     * The accessors to append to the model's array form.
     *
     * @var array<int, string>
     */
    protected $appends = [
        'fullname',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array<int, string>
     */
    protected $dates = [
        'deleted_at',
    ];

    /**
     * Scope a query with search.
     *
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeSearch(Builder $query, string $search)
    {
        return $query->where('name', 'like', '%'.$search.'%')
            ->orWhere('lastname', 'like', '%'.$search.'%')
            ->orWhere('email', 'like', '%'.$search.'%')
            ->orWhereHas('roles', function (Builder $query) use ($search): void {
                $query->where('description', 'like', '%'.$search.'%');
            });
    }

    /**
     * Get fullname accessor.
     */
    public function fullname(): Attribute
    {
        return Attribute::make(
            fn () => $this->name . ' ' . $this->lastname
        )->shouldCache();
    }

    /**
     * Get avatar accessor.
     */
    public function avatar(): Attribute
    {
        return Attribute::make(
            fn () => $this->attributes['avatar'] ?? 'https://www.gravatar.com/avatar/00000000000000000000000000000000?d=mp&s=200&f=y'
        )->shouldCache();
    }
}
