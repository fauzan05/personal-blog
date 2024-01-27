<?php

namespace App\Livewire;

use Livewire\Component;

class ContentPage extends Component
{
    public $post;
    public function mount($content)
    {
        $this->post = $content;
    }

    public function render()
    {
        return view('livewire.content-page');
    }
}
