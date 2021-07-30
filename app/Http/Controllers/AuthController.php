<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Http\Request\LoginRequest;
use App\Http\Request\RegisterRequest;
use App\Models\User;
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


class AuthController extends Controller
{
    protected $authRepository;

    public function __construct(AuthRepositoryInterface $authRepository)
    {
        $this->authRepository = $authRepository;
    }


    /**
     * Authenticate login user.
     *
     * @param LoginRequest $request
     *
     * @return JsonResponse
     */
    public function login(Request $request)
    {
        $rules = ['email' => 'required|string','password' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 422);
        }

        return $this->authRepository->login($request);
    }

    /**
     *  Register user.
     *
     * @param RegisterRequest $request
     *
     * @return JsonResponse
     */

    public function register(Request $request)
    {
        $rules = ['name' => 'required|string', 'email' => 'required|string|unique:users', 'password' => 'required|string'];
        $validator = Validator::make($request->all(), $rules);

        if ($validator->fails()) {
            return response()->json(['success' => 'false', 'message' => $validator->messages()], 422);
        }

        return $this->authRepository->register($request);
    }

    /**
     * Logout user.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function logout(Request $request)
    {

        if (Auth::user()) {
            Auth::logout();

            $request->session()->invalidate();
            $request->session()->regenerateToken();
        }
        return redirect()->route('login-view', app()->getLocale());
    }

    public function verify($locale, $token)
    {
        $data = explode('|', $token);
        $user = User::findOrFail($data[0]);
        if ($user->status == 1 || Auth::user()) {
            return redirect()->route('welcome', app()->getLocale());
        }
        $tokens = $user->tokens()->where('validate_till', '>=', Carbon::now())->get();
        if (count($tokens) > 0) {
            foreach ($tokens as $item) {
                if (Hash::check($data[1], $item->token)) {
                    $user->status = 1;
                    $user->save();
                    break;
                }
            }
        } else {
            $user->delete();
        }
        return redirect()->route('welcome', app()->getLocale());
    }


}
