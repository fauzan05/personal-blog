<?php

namespace App\Http\Middleware;

use App\Models\Menu;
use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class MenuIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!empty($request->name)) {
            $another_menu = Menu::where('name', $request->name)->first();
            if ($another_menu) {
                throw new HttpResponseException(response([
                    'status' => 'failed',
                    'code' => 409,
                    'message' => 'Conflict/Duplicate Menu Name',
                    'api_version' => 'v1',
                    'data' => null,
                    'error' => [
                        'error_message' => 'The menu name has already exist'
                    ]
                ], 409));
            }
        }
        if (!empty($request->idMenu)) {
            $current_menu = Menu::find($request->idMenu);
            $another_menu_accurate = Menu::where('name', $request->name_update)->first();
            $another_menu = Menu::where('name', 'like', '%' . $request->name_update . '%')->first();
            if (!empty($another_menu_accurate) && $current_menu->name != $another_menu_accurate->name && $current_menu->name != $another_menu_accurate->name && !empty($another_menu)) {
                throw new HttpResponseException(
                    response(
                        [
                            'status' => 'failed',
                            'code' => 409,
                            'message' => 'Conflict/Duplicate Category Name',
                            'api_version' => 'v1',
                            'data' => null,
                            'error' => [
                                'error_message' => 'The category name has already exist',
                            ],
                        ],
                        409,
                    ),
                );
            }
        }

        if (!empty($request->menu)) {
            $post = Post::where('slug', $request->menu)->first();
            // dd($post);
            if (!$post) {
                $menu = Menu::where('name', $request->menu)->first();
                if (!$menu) {
                    throw new HttpResponseException(
                        response(
                            [
                                'status' => 'failed',
                                'code' => 404,
                                'message' => 'Not Found',
                                'api_version' => 'v1',
                                'data' => null,
                                'error' => [
                                    'error_message' => "The menu has doesn't exist",
                                ],
                            ],
                            404,
                        ),
                    );
                }
            }
        }
        return $next($request);
    }
}
