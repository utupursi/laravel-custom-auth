<?php

namespace App\Repositories;

use App\Http\Request\LoginRequest;

use App\Http\Request\RegisterRequest;
use Illuminate\Http\Request;

interface AccessTokenRepositoryInterface
{
    public function create(Request $request);
    public function delete(Request $request);
}
