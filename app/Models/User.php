<?php

namespace App\Models;

use App\Models\Traits\ActiveUserTrait;
use App\Models\Traits\HasRelationshipTrait;
use App\Models\Traits\UserTrait;
use App\Support\Contracts\UserInterface;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\Auth;
use Tymon\JWTAuth\Contracts\JWTSubject;

class User extends Authenticatable implements UserInterface, JWTSubject
{
    use UserTrait, HasRelationshipTrait, ActiveUserTrait;
    use Notifiable {
        notify as protected inform;
    }

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'phone',
        'email',
        'password',
        'weixin_openid',
        'weixin_unionid',
        'registration_id',
        'introduction',
        'avatar',
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function topics()
    {
        return $this->hasMany(Topic::class);
    }

    /**
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function replies()
    {
        return $this->hasMany(Reply::class);
    }

    /**
     * 发送给定的通知。
     *
     * @param mixed $instance
     */
    public function notify($instance)
    {
        if ($this->id === Auth::id()) {
            return;
        }

        $this->increment('notification_count');
        $this->inform($instance);
    }

    /**
     * 标记已经浏览的通知。
     */
    public function markAsRead()
    {
        $this->notification_count = 0;
        $this->save();
        $this->unreadNotifications->markAsRead();
    }

    public function setAvatarAttribute($path)
    {
        if (! starts_with($path, 'http')) {
            $path = config('app.url') . "/uploads/images/avatars/$path";
        }
        $this->attributes['avatar'] = $path;
    }

    /**
     * 判断是否为原作者。
     *
     * @param \Illuminate\Database\Eloquent\Model $model
     *
     * @return bool
     */
    public function isAuthor(\Illuminate\Database\Eloquent\Model $model)
    {
        return $this->id === $model->user_id;
    }

    public function getJWTIdentifier()
    {
        return $this->getKey();
    }

    public function getJWTCustomClaims()
    {
        return [];
    }
}
