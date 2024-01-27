<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Post;
use App\Models\Tag;
use Illuminate\Http\Client\Pool;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Livewire\Component;
use Livewire\Attributes\On;

class AdminHome extends Component
{
    public $posts;
    public $categories;
    public $tags;
    public $comments;
    public function mount()
    {
        $this->getAllData();
    }

    public function getAllData()
    {
        $posts = Post::all()->count();
        $categories = Category::all()->count();
        $tags = Tag::all()->count();
        $comments = Comment::all()->count();
        $this->posts = $posts;
        $this->categories = $categories;
        $this->tags = $tags;
        $this->comments = $comments;
    }
    public function render()
    {
        return view('livewire.admin-home');
    }
}
