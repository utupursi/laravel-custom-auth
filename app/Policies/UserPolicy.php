<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserToken;
use Illuminate\Auth\Access\HandlesAuthorization;

class UserPolicy
{
    use HandlesAuthorization;

    /**
     * Create a new policy instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    public function check(User $user)
    {
        return true;
//        $userToken = UserToken::where(['access_token' => $token])->first();
//        return $userToken->user_id == $user->id;
    }
}
