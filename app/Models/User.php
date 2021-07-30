<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
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
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

//  we have added getUser here so we have flexibility of changing column  //  name in future without modifying at other places

    public function getUser($username)
    {
        return $this->where('email', $username)->first();
    }

    public function userTokens()
    {
        return $this->hasMany(UserToken::class, 'user_id')->orderBy('created_at','desc');
    }

    public function activeUserToken()
    {
        return $this->userTokens()->where('expires_at', '>=', Carbon::now()->format('Y-m-d h:i:s'))->first();
    }
}
