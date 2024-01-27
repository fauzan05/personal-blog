<?php

namespace App\Http\Controllers;

use App\Http\Resources\PostResource;
use App\Models\ApplicationSettings;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;
use Illuminate\Http\Request;

class MenuController extends Controller
{
    public function create(Request $request)
    {
        $response = Menu::create([
            'name' => $request->name
        ]);
        return response()->json(
            [
                'status' => 'success',
                'code' => 201,
                'message' => 'The menu has been successfully created',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
            201,
        );
    }

    public function update(int $id, Request $request)
    {
        $response = Menu::where('id', $id)->update([
            'name' => $request->name_update
        ]);
        $response = Menu::find($id);
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The menu has been successfully updated',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
            200,
        );
    }

    public function show()
    {
        $response = Menu::all();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The menu has been successfully showed',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
            200,
        );
    }

    public function showPost(string $menu, string $category = null)
    {
        $post = Post::where('slug', $menu);
        if($post->first() && empty($category)) {
            $response = $post->get();
        }
        // dd($post);
        $menu = Menu::where('name', $menu)->first();
        if ($menu && !empty($category)) {
            $category = Category::where('name', $category)->first();
            $response = $menu->posts()->where('category_id', $category->id)->paginate(12);
        } else if($menu && empty($category)){
            $response = $menu->posts()->paginate(12);
        }
        $app_settings = ApplicationSettings::first();
        $navbar_color = $app_settings->navbar_color;
        $navbar_text_color = $app_settings->navbar_text_color;
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The posts by menu has been successfully showed',
                'api_version' => 'v1',
                'data' => PostResource::collection($response)->response()
                    ->getData(true),
                'navbar_color' => $navbar_color,
                'navbar_text_color' => $navbar_text_color,
                'is_content' => !$post->first() ? false : true, //sebagai penanda bahwa ini konten atau berupa category/menu
                'error' => null,
            ],
            200,
        );
    }

    public function delete(int $id)
    {
        $response = Menu::find($id)->delete();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The menu has been successfully deleted',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
            200,
        );
    }
}
