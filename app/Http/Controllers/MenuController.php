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
    public $title_blog;

    public function getTitleBlog()
    {
        $this->title_blog = ApplicationSettings::first()->blog_name ?? "Untitled";
    }
    public function redirectTo(string $mainpath, string $secondpath = null, string $thirdpath = null, string $fourthpath = null)
    {
       
        $this->getTitleBlog();
        if (trim(strtolower($mainpath)) === 'about') {
            return view('app.about');
        }
        if ($mainpath && !$secondpath) {
            $is_menu = Menu::select('id', 'name')->where('name', $mainpath)->first();
            if ($is_menu) {
                // dd($menu->id);
                return view('app.home', ['title_blog' => $this->title_blog, 'current_menu_id' => $is_menu->id, 'current_menu_name' => $is_menu->name]);
            }
            if (!$is_menu) {
                $is_post = Post::select('id', 'title')->where('slug', $mainpath)->first();
                if ($is_post) {
                    return view('app.content', ['title_blog' => $this->title_blog, 'title_post' => $is_post->title, 'post_id' => $is_post->id]);
                }
                // return view('notfound');
            }
        }
        if ($mainpath && $secondpath && !$thirdpath && !$fourthpath) {
            $is_menu = Menu::select('id')->where('name', $mainpath)->first();
            if ($is_menu) {
                $is_category = Category::select('id', 'name')->where('name', $secondpath)->first();
                if ($is_category) {
                    return view('app.categories', [
                        'current_menu_id' => $is_menu->id,
                        'current_category_name' => $is_category->name, 'current_category_id' => $is_category->id,
                        'selected_month' => null, 'selected_year' => null
                    ]);
                }
            }
        }
        if ($mainpath && $secondpath && $thirdpath && !$fourthpath) {
            // mencari postingan yang memiliki tag di menu yang sama (hanya menampilkan postingan dengan tag di menu yang sama)
            $is_menu = Menu::select('id')->where('name', $mainpath)->first();
            if ($is_menu) {
                $is_category = Category::select('id', 'name')->where('name', $secondpath)->first();
                if ($is_category) {
                    return view('app.categories', [
                        'current_menu_id' => $is_menu->id,
                        'current_category_name' => $is_category->name, 'current_category_id' => $is_category->id,
                        'selected_month' => null, 'selected_year' => null
                    ]);
                }elseif(!$is_category) {
                    if($secondpath == 'tag') {
                        $show_tags_by_menu = $is_menu->posts;
                        foreach($show_tags_by_menu as $post):
                            foreach($post->tags as $tag):
                                $all_tags [] = $tag->name;
                                // Jika postingan yang memiliki tag yang sesuai dengan $thirdpath 
                                if(strtolower($tag->name) == strtolower($thirdpath)) {
                                    return view('app.tags', [
                                        'current_menu_id' => $is_menu->id,
                                        'current_tag_name' => $thirdpath,
                                        'current_tag_id' => $tag->id
                                    ]);
                                }
                            endforeach;
                        endforeach;
                        
                    }
                }
            }
        }
        if ($mainpath && $secondpath && $thirdpath && $fourthpath) {
            $is_menu = Menu::select('id')->where('name', $mainpath)->first();
            if ($is_menu) {
                $is_category = Category::select('id', 'name')->where('name', $secondpath)->first();
                if ($is_category) {
                    $post_is_exist = Post::select('id')->where('menu_id', $is_menu->id)
                        ->where('category_id', $is_category->id)->whereMonth('created_at', $thirdpath)
                        ->whereYear('created_at', $fourthpath)->first();
                    if ($post_is_exist) {
                        return view('app.categories', [
                            'current_menu_id' => $is_menu->id, 'current_category_name' => $is_category->name,
                            'current_category_id' => $is_category->id, 'selected_month' => $thirdpath, 'selected_year' => $fourthpath
                        ]);
                    }
                }
            }
        }

        return view('notfound');
    }
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
        if ($post->first() && empty($category)) {
            $response = $post->get();
        }
        // dd($post);
        $menu = Menu::where('name', $menu)->first();
        if ($menu && !empty($category)) {
            $category = Category::where('name', $category)->first();
            $response = $menu->posts()->where('category_id', $category->id)->paginate(12);
        } else if ($menu && empty($category)) {
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
