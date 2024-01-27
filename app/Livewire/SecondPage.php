<?php

namespace App\Livewire;

use Livewire\Component;
use Illuminate\Support\Facades\Http;

class SecondPage extends Component
{
    public $api_address;
    public $current_category;
    public $current_menu;
    public $months_years = [];
    public $count_month_years;
    public $posts = [];
    public $current_page;
    public $last_page; //total page
    public $selected_page = 1;
    public $navbar_color;
    public $navbar_text_color;
    public function mount($main_path, $second_path)
    {
        $this->api_address = config('services.api_address');
        $this->current_category = $second_path;
        $this->current_menu = $main_path;
        $this->getPostsByCategory();
    }

    public function getPostsByCategory()
    {
        $response = Http::get($this->api_address . "menu/$this->current_menu/$this->current_category");
        $response = json_decode($response->body(), JSON_OBJECT_AS_ARRAY);
        $this->posts = $response['data']['data'];
        foreach($this->posts as $post):
            $this->months_years [] = $post['month_year'];
        endforeach;
        $this->months_years = array_count_values($this->months_years);
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
        return view('livewire.second-page');
    }
}
