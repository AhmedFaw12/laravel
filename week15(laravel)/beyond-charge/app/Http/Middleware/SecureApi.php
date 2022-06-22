<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class SecureApi
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        if($request->header("API_KEY") == env("API_KEY")){

            return $next($request);
        }else{
            return response()->json(["error"=>"Invalid API Key"],403);
        }
    }
}
