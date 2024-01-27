<?php

namespace App\Http\Middleware;

use App\Models\User;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class EmailIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $currentUser = User::find(auth()->user()->id);
        $anotherUser = User::where('username', 'like', '%' . trim($request->email) . '%')->first();
        if($anotherUser && $currentUser->email != $request->email)
        {
            throw new HttpResponseException(response([
                'status' => 'failed',
                'code' => 409,
                'message' => 'Conflict/Duplicate Email',
                'api_version' => 'v1',
                'data' => null,
                'error' => [
                    'error_message' => "Email has already used"
                ]       
            ], 409));
        }
        return $next($request);
    }
}
