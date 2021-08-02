<?php

namespace App\Http\Middleware;

use App\Models\UserRequestLog;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogAuthorisedRequest
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
        UserRequestLog::create([
            'user_id' => $request->bearer->user_id,
            'token_id' => $request->bearer->access_token,
            'request_method' => $request->method(),
            'request_params' => $request->getQueryString() ?: ""
        ]);
        return $next($request);
    }
}
