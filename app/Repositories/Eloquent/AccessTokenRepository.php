<?php

namespace App\Repositories\Eloquent;

use App\Http\Request\LoginRequest;
use App\Http\Request\RegisterRequest;
use App\Models\User;
use App\Models\UserToken;
use App\Repositories\AccessTokenRepositoryInterface;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\Eloquent\Base\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AccessTokenRepository extends BaseRepository implements AccessTokenRepositoryInterface
{

    public function __construct(UserToken $model)
    {
        parent::__construct($model);
    }

    /**
     * Create access token
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function create(Request $request)
    {
        $this->model->create([
            'access_token' => $request['access_token'],
            'expires_at' => Carbon::now()->addDays(30)->format('Y-m-d h:i:s'),
            'user_id' => $request->bearer->user_id
        ]);

        return response()->json(
            [
                'success' => 'true',
                'message' => 'Access token was successfully saved'
            ]);
    }

    /**
     * Delete access token.
     *
     * @param Request $request
     * @return JsonResponse
     */
    public function delete(Request $request)
    {
        $userToken = $this->model->where(['access_token' => $request['access_token']]);
        if ($userToken) {
            $userToken->delete();
            return response()->json([
                'success' => 'true',
                'message' => 'Access token was successfully deleted'
            ]);
        }

        return response()->json(
            [
                'success' => 'false',
                'message' => 'Access token was not deleted'
            ], 400);
    }


}
