<?php

namespace App\Http\Middleware;

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
        $bearer = UserToken::where(['access_token' => $request->bearerToken()])
            ->where('expires_at', '>=', Carbon::now()->format('Y-m-d h:i:s'))
            ->first();

        if ($bearer) {
            $request->merge(['bearer' => $bearer]);
            return $next($request);
        }

        return response()->json([
            'success' => 'false',
            'message' => 'Unauthorised action'
        ], 401);

    }
}
