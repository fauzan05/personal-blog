<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserIdIsUnique
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $user = User::find($request->user_id);
        if($user)
        {
            throw new HttpResponseException(response([
                'status' => 'failed',
                'code' => 409,
                'message' => 'Conflict/Duplicate User Id',
                'api_version' => 'v1',
                'data' => null,
                'error' => [
                    'error_message' => 'The user id has already exist'
                ]       
            ], 409));
        }
        return $next($request);
    }
}
