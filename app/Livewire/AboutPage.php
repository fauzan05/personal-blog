<?php

namespace App\Livewire;

use App\Models\ApplicationSettings;
use App\Models\Note;
use App\Models\User;
use App\Rules\MaxWords;
use Livewire\Component;
use Livewire\Attributes\Validate;

class AboutPage extends Component
{
    public $profile_image;
    public $logo_filename;

    #[Validate('required|min:3')] 
    public $notes;
    public $title;
    public $content;
    public $author;

    public function mount()
    {
        $this->getProfile();
        $this->getNotes();
    }

    public function getProfile()
    {
        $user = User::where('role', 'admin')->first();
        $this->profile_image = $user->profile_photo_filename;
        $logo = ApplicationSettings::first()->logo_filename;
        $this->logo_filename = $logo;
    }

    public function getNotes()
    {
        $notes = Note::orderBy('created_at', 'desc')->get()->toArray();
        $this->notes = $notes;
    }

    public function createNote()
    {
        $validate = $this->validate([
            'content' => ['required', 'string', new MaxWords],
            'title' => ['required', 'string', 'max:50', 'min:3'],
            'author' => ['required', 'string', 'max:50', 'min:3']
        ]);
        $note = Note::create([
            'content' => $validate['content'],
            'title' => $validate['title'],
            'author' => $validate['author']
        ]);
        $this->reset('content');
        $this->reset('title');
        $this->reset('author');
        $this->getNotes();
        $this->dispatch('create-notes'); // merefresh tampilan js
    }

    public function render()
    {
        return view('livewire.about-page');
    }
}
