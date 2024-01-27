<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class MainPage extends Component
{
    public $posts;
    public $current_menu;
    public $current_page;
    public $last_page; //total page
    public $api_address;
    public $selected_page = 1;
    public $navbar_color;
    public $navbar_text_color;
    public $categories;

    public function mount($main_path)
    {
        $this->api_address = config('services.api_address');
        $this->current_menu = $main_path;
        $this->showPostByMenu();
    }

    public function showPostByMenu()
    {
        $response = Http::get($this->api_address . "menu/$this->current_menu");
        $response = json_decode($response->body(), JSON_OBJECT_AS_ARRAY);
        $this->posts = $response['data']['data'];
        foreach ($this->posts as $post) :
            $this->categories[] = $post['category']['name'];
        endforeach;
        $this->categories = array_unique($this->categories);
        $this->categories = array_unique($this->categories);
        // dd($this->categories);
        $this->current_page = $response['data']['meta']['current_page'];
        $this->last_page = $response['data']['meta']['last_page'];
        $this->navbar_color = $response['navbar_color'];
        $this->navbar_text_color = $response['navbar_text_color'];
    }

    public function getPage($page)
    {
        $this->selected_page = $page;
        $response = Http::get($this->api_address . "menu/$this->current_menu?page=$page");
        $response = json_decode($response->body(), JSON_OBJECT_AS_ARRAY);
        $this->posts = $response['data']['data'];
    }

    public function render()
    {
        return view('livewire.main-page');
    }
}
