<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSettings;
use App\Models\Category;
use App\Models\Menu;
use App\Models\Post;

class MenuController extends Controller
{
    public $title_blog;
    public $logo_filename;

    public function __construct()
    {
        $this->logo_filename = ApplicationSettings::select('logo_filename')->first()->logo_filename;
    }

    public function getTitleBlog()
    {
        $this->title_blog = ApplicationSettings::first()->blog_name ?? "Untitled";
    }
    public function redirectTo(string $mainpath, string $secondpath = null, string $thirdpath = null, string $fourthpath = null)
    {
       
        $this->getTitleBlog();
        if (trim(strtolower($mainpath)) === 'about') {
            return view('app.about', ['logo_filename' => $this->logo_filename]);
        }
        if ($mainpath && !$secondpath) {
            $is_menu = Menu::select('id', 'name')->where('name', $mainpath)->first();
            if ($is_menu) {
                // dd($menu->id);
                return view('app.home', ['title_blog' => $this->title_blog, 'current_menu_id' => $is_menu->id, 'current_menu_name' => $is_menu->name, 'logo_filename' => $this->logo_filename]);
            }
            if (!$is_menu) {
                $is_post = Post::select('id', 'title')->where('slug', $mainpath)->first();
                if ($is_post) {
                    return view('app.content', ['title_blog' => $this->title_blog, 'title_post' => $is_post->title, 'post_id' => $is_post->id, 'logo_filename' => $this->logo_filename]);
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
                        'selected_month' => null, 'selected_year' => null,
                        'logo_filename' => $this->logo_filename
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
                        'selected_month' => null, 'selected_year' => null,
                        'logo_filename' => $this->logo_filename
                    ]);
                }elseif(!$is_category) {
                    if($secondpath == 'tag') {
                        $show_tags_by_menu = $is_menu->posts;
                        foreach($show_tags_by_menu as $post):
                            foreach($post->tags as $tag):
                                // $all_tags [] = $tag->name;
                                // Jika postingan yang memiliki tag yang sesuai dengan $thirdpath 
                                if(strtolower($tag->name) == strtolower($thirdpath)) {
                                    return view('app.tags', [
                                        'current_menu_id' => $is_menu->id,
                                        'current_tag_name' => $thirdpath,
                                        'current_tag_id' => $tag->id,
                                        'logo_filename' => $this->logo_filename
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
                            'current_category_id' => $is_category->id, 'selected_month' => $thirdpath, 'selected_year' => $fourthpath,
                            'logo_filename' => $this->logo_filename
                        ]);
                    }
                }
            }
        }

        return view('notfound', ['logo_filename' => $this->logo_filename]);
    }
}
