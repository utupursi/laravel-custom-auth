<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Request\LoginRequest;
use App\Http\Request\RegisterRequest;
use App\Models\User;
use App\Repositories\AccessTokenRepositoryInterface;
use App\Repositories\AuthRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Psy\Util\Json;


class AccessTokenController extends Controller
{
    protected $accessTokenRepository;

    public function __construct(AccessTokenRepositoryInterface $accessTokenRepositoryRepository)
    {
        $this->accessTokenRepository = $accessTokenRepositoryRepository;
    }

    /**
     *  Register user.
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */

    public function create(Request $request)
    {
        $rules = ['access_token' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 422);
        }

        return $this->accessTokenRepository->create($request);
    }

    public function delete(Request $request)
    {
        $rules = ['access_token' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 422);
        }

        return $this->accessTokenRepository->delete($request);
    }

}
