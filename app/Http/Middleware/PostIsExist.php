<?php

namespace App\Http\Middleware;

use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class PostIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty($request->idPost)) {
            $post = Post::find($request->idPost);
            if (!$post) {
                throw new HttpResponseException(response([
                    'status' => 'failed',
                    'code' => 404,
                    'message' => 'Not Found',
                    'api_version' => 'v1',
                    'data' => null,
                    'error' => [
                        'error_message' => 'The post not found'
                    ]
                ], 404));
            }
            if (!empty($request->title && $post)) {
                $current_post = $post->title;
                $post_accurate = Post::where('title', trim($request->title))->first();
                $post = Post::where('title', 'like', '%' . trim($request->title) . '%')->first();
                if($post_accurate && $post && $post_accurate->title != $current_post && $post->title != $current_post) {
                    throw new HttpResponseException(response([
                        'status' => 'failed',
                        'code' => 409,
                        'message' => 'Conflict/Duplicate Title Post',
                        'api_version' => 'v1',
                        'data' => null,
                        'error' => [
                            'error_message' => 'The post title has been already used'
                        ]
                    ], 409));
                }
            }
        }
        if (empty($request->idPost)) {
            $post_accurate = Post::where('title', trim($request->title))->first();
            $post = Post::where('title', 'like', '%' . trim($request->title) . '%')->first();
            if ($post_accurate && $post) {
                throw new HttpResponseException(response([
                    'status' => 'failed',
                    'code' => 409,
                    'message' => 'Conflict/Duplicate Title Post',
                    'api_version' => 'v1',
                    'data' => null,
                    'error' => [
                        'error_message' => 'The post title has been already used'
                    ]
                ], 409));
            }
        }


        return $next($request);
    }
}
