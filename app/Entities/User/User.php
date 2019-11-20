<?php
namespace App\Entities\User;

use Spatie\Permission\Traits\HasRoles;
use Tymon\JWTAuth\Contracts\JWTSubject;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\SoftDeletes;
use Prettus\Repository\Contracts\Transformable;
use Prettus\Repository\Traits\TransformableTrait;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;

/**
 * Class User.
 *
 * @package namespace App\Entities\User;
 */
class User extends Authenticatable implements JWTSubject, Transformable
{
    use TransformableTrait, Notifiable, HasRoles, SoftDeletes;

    protected $table = 'user';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name', 'email', 'password', 'mobile', 'openid', 'nickname', 'headimgurl', 'mobile_verified_at', 'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    /**
     * Get the identifier that will be stored in the subject claim of the JWT.
     *
     * @return mixed
     */
    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    /**
     * Return a key value array, containing any custom claims to be added to the JWT.
     *
     * @return array
     */
    public function getJWTCustomClaims()
    {
        return [];
    }

    /**
     * 查询手机号
     *
     * @param $query
     * @param $mobile
     * @return \Illuminate\Database\Eloquent\Builder
     */
    public function scopeMobile($query, $mobile)
    {
        return $query->where('mobile', $mobile);
    }

    /**
     * 一对多 关联 公众号
     * 
     * @return HasMany
     */
    public function apps(): HasMany
    {
        return $this->hasMany('App\Entities\WeChat\App', 'user_id', 'id');
    }
}
