<?php

namespace App\Http\Middleware;

use App\Models\Tag;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class TagIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty($request->name)) {
            $tag = Tag::where('name', 'like', '%' . $request->name . '%')->first();
            if ($tag) {
                throw new HttpResponseException(
                    response(
                        [
                            'status' => 'failed',
                            'code' => 409,
                            'message' => 'Conflict/Duplicate Tag Name',
                            'api_version' => 'v1',
                            'data' => null,
                            'error' => [
                                'error_message' => 'The tag name has already exist',
                            ],
                        ],
                        409,
                    ),
                );
            }
        }

        if (!empty($request->idTag)) {
            $tag = Tag::find($request->idTag);
            if (!$tag) {
                throw new HttpResponseException(
                    response(
                        [
                            'status' => 'failed',
                            'code' => 404,
                            'message' => 'Not Found',
                            'api_version' => 'v1',
                            'data' => null,
                            'error' => [
                                'error_message' => "The tag is doesn't exist",
                            ],
                        ],
                        404,
                    ),
                );
            }
        }

        if(!empty($request->update_name))
        {
            $currentTag = Tag::find($request->idTag);
            $otherTag = Tag::where('name', 'like', '%' . $request->update_name . '%')->first();
            $otherTagAccurate = Tag::where('name', trim($request->update_name))->first();
            if(!empty($otherTag) && !empty($otherTagAccurate) && $otherTag->name != $currentTag->name && $otherTagAccurate->name != $currentTag->name) {
                throw new HttpResponseException(
                    response(
                        [
                            'status' => 'failed',
                            'code' => 409,
                            'message' => 'Not Found',
                            'api_version' => 'v1',
                            'data' => null,
                            'error' => [
                                'error_message' => "The tag name is already exist",
                            ],
                        ],
                        409,
                    ),
                );
            }
        }

        return $next($request);
    }
}
