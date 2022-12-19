<?php

namespace App\Http\Middleware;

use App\Models\RequestLog;
use App\Models\ResponseLog;
use Closure;
use Illuminate\Http\Request;

class DatabaseLogger
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
        $new_req = new RequestLog();
        if($user != null)
        {
            
            $new_req->host = $request->host();
            $new_req->method = $request->method();
            $new_req->url = $request->path();
            $new_req->accept = $request->header('accept');
            $new_req->accept_encoding = $request->header('accept-encoding');
            $new_req->connection = $request->header('connection');
            $new_req->content_type = $request->header('content-type');
            $new_req->content_length = $request->header('content-length');
            $new_req->user_agent = $request->header('user-agent');
            $new_req->body = json_encode($request->all());
            $new_req->user_id = $request->user()->id;
            error_log(json_encode($request->all()));
            $new_req->save();
        }
        else
        {
            $new_req->host = $request->host();
            $new_req->method = $request->method();
            $new_req->url = $request->path();
            $new_req->accept = $request->header('accept');
            $new_req->accept_encoding = $request->header('accept-encoding');
            $new_req->connection = $request->header('connection');
            $new_req->content_type = $request->header('content-type');
            $new_req->content_length = $request->header('content-length');
            $new_req->user_agent = $request->header('user-agent');
            $new_req->body = json_encode($request->all());
            error_log(json_encode($request->all()));
            $new_req->save();
        }
        $response = $next($request);
        $new_res = new ResponseLog();
        $new_res->request_id = $new_req->id;
        $new_res->content = $response;
        $new_res->save();
        
        return $response;
    }
}
