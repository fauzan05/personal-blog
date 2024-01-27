<?php

namespace App\Http\Middleware;

use App\Models\SocialMedia;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class SocialMediaIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $socialMedia = SocialMedia::find($request->idSocialMedia);
        if(!$socialMedia)
        {
            throw new HttpResponseException(response([
                'status' => 'failed',
                'code' => 404,
                'message' => 'Not Found',
                'api_version' => 'v1',
                'data' => null,
                'error' => [
                    'error_message' => 'The social media not found'
                ]       
            ], 404));
        }
        return $next($request);
    }
}
