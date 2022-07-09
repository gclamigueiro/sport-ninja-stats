<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Redis;

class StatsCacheMiddleware
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
        $stat = $request->query('stat', '');
        
        $key = 'stats_' . $stat;
        $cached = Redis::get($key);

        if($cached){
            return response()->json(json_decode($cached));
        }
        
        $response =  $next($request);

        Redis::set($key, $response->getContent()); 

        return $response;
    }
}
