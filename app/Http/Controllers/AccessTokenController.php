<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Request\LoginRequest;
use App\Http\Request\RegisterRequest;
use App\Models\User;
use App\Models\UserToken;
use App\Repositories\AccessTokenRepositoryInterface;
use App\Repositories\AuthRepositoryInterface;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Gate;
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
        $userToken = UserToken::where(['user_id' => $request->bearer->user_id])->first();

        $user = User::find($request->bearer->user_id);

        if (!Gate::forUser($user)->allows('isVerified')) {
            return response()->json(['success' => 'false', 'message' => "User is not verified"], 422);
        }

        $rules = ['access_token' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 422);
        }

        return $this->accessTokenRepository->create($request);
    }

    public function delete(Request $request)
    {
        $user = User::find($request->bearer->user_id);
        return $user->can('check');
        if ($user->cannot('checkOwner', $request->post('access_token'))) {
            return response()->json(
                [
                    'success' => 'false', 'message' => 'you are not owner of this token'
                ]);
        }

        $rules = ['access_token' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 422);
        }

        return $this->accessTokenRepository->delete($request);
    }

}
