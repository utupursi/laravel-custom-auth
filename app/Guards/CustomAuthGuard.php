<?php

namespace App\Guards;


use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Contracts\Auth\Guard;
use Illuminate\Contracts\Auth\UserProvider;
use Illuminate\Http\Request;

class CustomAuthGuard implements Guard
{

    private $request;
    private $provider;
    private $user;

    public function __construct(UserProvider $provider, Request $request)
    {
        $this->request = $request;
        $this->provider = $provider;
        $this->user = null;
    }

    public function check()
    {
        return isset($this->user);
    }

    public function guest()
    {
        return !isset($this->user);
    }

    public function user()
    {
        if (isset($this->user)) {
            return $this->user;
        }
    }

    public function id()
    {
        if (isset($this->user)) {
            return $this->user->getAuthIdentifier();
        }
    }

    public function validate(array $credentials = [])
    {
        if (!isset($credentials['username']) || empty($credentials['username']) || !isset($credentials['password']) || empty($credentials['password'])) {
            return false;
        }


        $user = $this->provider->retrieveById($credentials['username']);

        if ($user && $this->provider->validateCredentials($user, $credentials)) {
            $this->setUser($user);
            return true;
        }
        return false;

    }

    public function setUser(Authenticatable $user)
    {
        $this->user = $user;
    }
}
