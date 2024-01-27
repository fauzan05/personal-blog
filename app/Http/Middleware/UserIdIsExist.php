<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserIdIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(!empty($request->user()->id))
        {
            $user = User::find($request->user()->id);
            if(!$user)
            {
                throw new HttpResponseException(response([
                    'status' => 'failed',
                    'code' => 404,
                    'message' => 'Not Found',
                    'api_version' => 'v1',
                    'data' => null,
                    'error' => [
                        'error_message' => "The user id is doesn't exist"
                    ]       
                ], 404));
            }
        }
        
        if(!empty($request->user_id))
        {
            $user = User::find($request->user_id);
            if(!$user)
            {
                throw new HttpResponseException(response([
                    'status' => 'failed',
                    'code' => 404,
                    'message' => 'Not Found',
                    'api_version' => 'v1',
                    'data' => null,
                    'error' => [
                        'error_message' => "The user id is doesn't exist"
                    ]       
                ], 404));
            }
        }
        return $next($request);
    }
}
