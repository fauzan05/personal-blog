<?php

namespace App\Livewire;

use App\Models\Category;
use App\Models\Comment;
use App\Models\Note;
use App\Models\Post;
use App\Models\Tag;
use Livewire\Component;

class AdminHome extends Component
{
    public $posts;
    public $categories;
    public $tags;
    public $comments;
    public $notes;
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
        $notes = Note::all()->toArray();
        $this->notes = $notes;
    }

    public function deleteNote($id, $title)
    {
        Note::find($id)->delete();
        session()->now('status', 'Berhasil Menghapus Catatan Dengan Judul ' . $title);
        $this->notes = Note::all()->toArray();
    }
    public function render()
    {
        return view('livewire.admin-home');
    }
}
