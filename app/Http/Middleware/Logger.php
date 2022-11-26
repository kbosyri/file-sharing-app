<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class Logger
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
        $user = $request->user();
        if($user != null)
        {
            Log::channel('http')->info("Request Captured",[
                'IP Address'=>$request->ip(),
                'URL' =>$request->path(),
                'Host'=>$request->host(),
                'Method'=>$request->method(),
                'User' =>$request->user()->email,
                'Token'=>$request->bearerToken(),
                "Inputs"=>$request->all(),
            ]);
        }
        else
        {
            Log::channel('http')->info("Request Captured",[
                'IP Address'=>$request->ip(),
                'URL' =>$request->path(),
                'Host'=>$request->host(),
                'Method'=>$request->method(),
                'User' =>'none',
                "Inputs"=>$request->all(),
                "request"=>$request,
            ]);
        }
        $response = $next($request);
        Log::channel('http')->info("Response Caught",[
            'response'=>$response,
        ]);
        return $response;
    }
}
