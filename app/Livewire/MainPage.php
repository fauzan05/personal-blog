<?php

namespace App\Livewire;

use App\Models\ApplicationSettings;
use App\Models\Category;
use App\Models\Comment;
use App\Models\Media;
use App\Models\Post;
use Carbon\Carbon;
use Livewire\Component;
use Illuminate\Support\Facades\Http;

class MainPage extends Component
{
    public $posts;
    public $current_menu_id;
    public $current_menu_name;
    public $current_page;
    public $last_page; //total page
    public $selected_page = 1;
    public $navbar_color;
    public $navbar_text_color;
    public $categories;

    public function mount($current_menu_id, $current_menu_name)
    {
        $this->current_menu_id = $current_menu_id;
        $this->current_menu_name = $current_menu_name;
        $this->showPostByMenu();
    }

    public function showPostByMenu()
    {
        
        $posts = Post::with('category', 'media', 'comments')->where('menu_id', $this->current_menu_id);
        $this->posts = array_map(function($post) {
            $category = Category::find($post['category_id']);
            $image = Media::select('name')->where('post_id', $post['id'])->first();
            $comment = Comment::where('post_id', $post['id'])->get();
            $createdAt = Carbon::parse($post['created_at'])->setTimezone('Asia/Jakarta')->format('F j, Y');
            return [
                'id' => $post['id'],
                'user_id' => $post['user_id'],
                'category' => $category->toArray(),
                'title' => $post['title'],
                'slug' => $post['slug'],
                'image' => $image ? $image->name : null,
                'total_comments' => (integer)count($comment->toArray()),
                'created_at' => $createdAt
            ];
        }, $posts->paginate(12)->toArray()['data']);
        $this->last_page = $posts->paginate(12)->lastPage();
        $this->current_page = $posts->paginate(12)->currentPage();
        $categories = Post::select('category_id')->where('menu_id', $this->current_menu_id)->get();
        $total_categories = [];
        foreach($categories as $category):
            $total_categories[] = $category->category->name;
        endforeach;
        $this->categories = $total_categories;
        if($this->categories) {
            $this->categories = array_unique($this->categories);
        }
    }

    public function getPage($page)
    {
        $this->selected_page = $page;
        $posts = Post::with('category', 'media', 'comments')->where('menu_id', $this->current_menu_id)->paginate(12, ['*'], 'page', $this->selected_page); 
        $this->posts = array_map(function($post) {
            $category = Category::find($post['category_id']);
            $image = Media::select('name')->where('post_id', $post['id'])->first();
            $comment = Comment::where('post_id', $post['id'])->get();
            $createdAt = Carbon::parse($post['created_at'])->setTimezone('Asia/Jakarta')->format('F j, Y');
            return [
                'id' => $post['id'],
                'user_id' => $post['user_id'],
                'category' => $category->toArray(),
                'title' => $post['title'],
                'slug' => $post['slug'],
                'image' => $image ? $image->name : null,
                'total_comments' => (integer)count($comment->toArray()),
                'created_at' => $createdAt
            ];
        }, $posts->toArray()['data']);
        $this->last_page = $posts->lastPage();
        $this->current_page = $posts->currentPage();
        foreach ($this->posts as $post) :
            $this->categories[] = $post['category']['name'];
        endforeach;
        if($this->categories) {
        $this->categories = array_unique($this->categories);
        }
    }

    public function render()
    {
        return view('livewire.main-page');
    }
}
