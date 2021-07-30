<?php

namespace App\Repositories\Eloquent;

use App\Http\Request\LoginRequest;
use App\Http\Request\RegisterRequest;
use App\Models\User;
use App\Repositories\AuthRepositoryInterface;
use App\Repositories\Eloquent\Base\BaseRepository;
use Carbon\Carbon;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class AuthRepository extends BaseRepository implements AuthRepositoryInterface
{

    public function __construct(User $model)
    {
        parent::__construct($model);
    }

    /**
     * Authenticate login user.
     *
     * @param Request $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $credentials = [
            'username' => $request->post('email'),
            'password' => $request->post('password'),
        ];

        if (!Auth::guard('token')->validate($credentials)) {
//            return Auth::guard('token')->validate($credentials);
            return response()->json([
                'success' => 'false',
                'message' => 'Bad credentials'
            ], 401);
        }
        $token = Auth::guard('token')->user()->activeUserToken();
        if (!$token) {
            $token = Auth::guard('token')->user()->userTokens()->create([
                'access_token' => Str::random(40),
                'expires_at' => Carbon::now()->addDays(30)->format('Y-m-d h:i:s'),
                'user_id' => Auth::guard('token')->user()->id
            ]);
        }
        return response()->json([
            'success' => 'true',
            'message' => 'You are successfully log in',
            'token' => $token
        ]);
    }

    /**
     * Create Feature item into db.
     *
     * @param RegisterRequest $request
     * @return JsonResponse
     */
    public function register(Request $request)
    {
        DB::beginTransaction();
        $model = $this->model->create([
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => Hash::make($request['password']),
        ]);

        $token = Str::random(40);
        if ($model) {
            $userToken = $model->userTokens()->create([
                'access_token' => $token,
                'expires_at' => Carbon::now()->addDays(30)->format('Y-m-d h:i:s'),
                'user_id' => $model->id
            ]);
            if ($userToken) {
                DB::commit();
                return response()->json([
                    'success' => 'true',
                    'message' => 'User was successfully created',
                ]);
            }
        }
        DB::rollBack();
        return response()->json(['success' => 'false',
            'message' => 'User was not created',
        ]);

//        $token = Str::random(40);
//        $model->tokens()->create([
//            'token' => Hash::make($token),
//            'validate_till' => Carbon::now()->addDays(1)
//        ]);
    }

//    /**
//     * Logout user.
//     *
//     * @param \Illuminate\Http\Request $request
//     *
//     * @return \Illuminate\Http\Response
//     */
//    public function logout(Request $request)
//    {
//
//        if (Auth::user()) {
//            Auth::logout();
//
//            $request->session()->invalidate();
//            $request->session()->regenerateToken();
//        }
//        return redirect()->route('login-view', app()->getLocale());
//    }

    protected function getLocalization(string $lang)
    {
        $localization = Language::where('abbreviation', $lang)->first();
        if (!$localization) {
            throw new Exception('Localization not exist.');
        }

        return $localization;
    }


}
