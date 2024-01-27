<?php

namespace App\Http\Middleware;

use App\Models\Media;
use Closure;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\Response;

class MediaIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $image_filename = Media::where('post_id', $request->idPost)->first()->name;
        if(!$image_filename) {
            throw new HttpResponseException(response([
                'status' => 'failed',
                'code' => 404,
                'message' => 'Not Found',
                'api_version' => 'v1',
                'data' => null,
                'error' => [
                    'error_message' => 'The image file not set in database'
                ]       
            ], 404));
        }
        $image_file = Storage::exists('public/images/' . $image_filename);
        if(!$image_file) {
            throw new HttpResponseException(response([
                'status' => 'failed',
                'code' => 404,
                'message' => 'Not Found',
                'api_version' => 'v1',
                'data' => null,
                'error' => [
                    'error_message' => 'The image file not found'
                ]       
            ], 404));
        }
        return $next($request);
    }
}
