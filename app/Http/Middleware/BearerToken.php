<?php

namespace App\Http\Middleware;

use App\Models\User;
use App\Models\UserToken;
use Carbon\Carbon;
use Closure;
use Illuminate\Http\Request;

class BearerToken
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->get('token')) {
            $token = UserToken::where(['access_token' => $request->get('token')])
                ->where('expires_at', '>=', Carbon::now()->format('Y-m-d h:i:s'))
                ->first();
        } else {
            $token = UserToken::where(['access_token' => $request->bearerToken()])
                ->where('expires_at', '>=', Carbon::now()->format('Y-m-d h:i:s'))
                ->first();
        }

        if ($token) {
            $user = User::find($token->user_id);
            $request->merge(['bearer' => $token, 'user' => $user]);
            return $next($request);
        }


        return response()->json([
            'success' => 'false',
            'message' => 'Unauthorised action'
        ], 401);

    }
}
