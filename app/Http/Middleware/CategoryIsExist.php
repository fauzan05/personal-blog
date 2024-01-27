<?php

namespace App\Http\Middleware;

use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Http\Exceptions\HttpResponseException;

class CategoryIsExist
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if(empty($request->idCategory)) {
            $category = Category::where('name', 'like', '%' . $request->name . '%')->first();
            $category_accurate = Category::where('name', $request->name)->first();
            if($category && $category_accurate) {
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
        if(!empty($request->idCategory)) {
            $category = Category::find($request->idCategory);
            if(!$category) {
                throw new HttpResponseException(
                    response(
                        [
                            'status' => 'failed',
                            'code' => 404,
                            'message' => 'Not Found',
                            'api_version' => 'v1',
                            'data' => null,
                            'error' => [
                                'error_message' => "The category has doesn't exist",
                            ],
                        ],
                        404,
                    ),
                );
            }
        }

        if(!empty($request->update_name)) {
            $currentCategory = Category::find($request->idCategory);
            $otherCategory = Category::where('name', 'like', '%' . $request->update_name . '%')->first();
            $otherCategoryAccurate = Category::where('name', $request->update_name)->first();
            if(!empty($otherCategory) && $otherCategory->name != $currentCategory->name && !empty($otherCategoryAccurate) && $otherCategoryAccurate->name != $currentCategory->name) {
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
        if(!empty($request->category) && !empty($request->menu)) {
            $category = Category::where('name', $request->category)->first();
            if(!$category) {
                throw new HttpResponseException(
                    response(
                        [
                            'status' => 'failed',
                            'code' => 404,
                            'message' => 'Not Found',
                            'api_version' => 'v1',
                            'data' => null,
                            'error' => [
                                'error_message' => "The category has doesn't exist",
                            ],
                        ],
                        404,
                    ),
                );
            }
            if($category) {
                $menu = Menu::where('name', $request->menu)->first();
                if(!$menu) {
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
                // if($menu) {
                //     dd($menu->posts->where('category_id', $category->id)->first());
                // }
            }
        }
        return $next($request);
    }
}
