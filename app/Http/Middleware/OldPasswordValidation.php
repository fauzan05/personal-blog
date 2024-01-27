<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class OldPasswordValidation
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = User::find(auth()->user()->id)->first();
        if(!$currentUser)
        {
            throw new HttpResponseException(response([
                'status' => 'failed',
                'code' => 401,
                'message' => 'Validation Error',
                'api_version' => 'v1',
                'data' => null,
                'error' => [
                    'error_message' => 'The user has not been logged in'
                ]       
            ], 401));
        }
        if(!Hash::check($request->old_password, $currentUser->password))
        {
            throw new HttpResponseException(response([
                'status' => 'failed',
                'code' => 401,
                'message' => 'Validation Error',
                'api_version' => 'v1',
                'data' => null,
                'error' => [
                    'error_message' => 'The old password is wrong'
                ]       
            ], 401));
        }
        return $next($request);
    }
}
