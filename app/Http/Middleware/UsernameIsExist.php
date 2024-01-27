<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class UsernameIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = User::find(auth()->user()->id);
        $anotherUser = User::where('username', 'like', '%' . trim($request->username) .'%')->first();
        if($anotherUser && $currentUser->username != $request->username)
        {
            throw new HttpResponseException(response([
                'status' => 'failed',
                'code' => 409,
                'message' => 'Conflict/Duplicate Username',
                'api_version' => 'v1',
                'data' => null,
                'error' => [
                    'error_message' => "Username has already used"
                ]       
            ], 409));
        }
        return $next($request);
    }
}
