<?php

namespace App\Livewire;

use App\Models\ApplicationSettings;
use App\Models\Menu;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;
use Livewire\Component;
use Livewire\Features\SupportRedirects\Redirector;

class NavbarComponent extends Component
{
    public $token;
    public $darkModeState;
    public $menus;
    public $website_name;
    public $logo;
    public $navbar_color;
    public $navbar_text_color;
    public $current_url;
    public $application_settings;
    public $show_blog_name = false;
    public $postTitle;

    public $show_sidebar_state = false;

    public function mount()
    {
        (bool)$this->darkModeState = (bool)Cookie::get('dark-mode') ? true : false;
        $this->getNavbar();
        $current_url = Request::url();
        $parseUrl = parse_url($current_url);
        if(!empty($parseUrl['path'])) {
            $pathSegments = explode('/', $parseUrl['path']);
            $this->current_url = $pathSegments[1];
        }
    }
    public function getNavbar()
    {
        $this->menus = Menu::all();
        $this->application_settings = ApplicationSettings::first();
        $this->website_name = $this->application_settings->blog_name ?? "Untitled";
        $this->logo = $this->application_settings->logo_filename ?? "Untitled";
        $this->navbar_color = $this->application_settings->navbar_color ?? null;
        $this->navbar_text_color = $this->application_settings->navbar_text_color ?? null;
        $this->show_blog_name = $this->application_settings->show_blog_name ?? false;
        if($this->navbar_color && $this->navbar_text_color) {
            $this->dispatch('navbar-text-color', data: $this->navbar_text_color);
            $this->dispatch('navbar-color', data: $this->navbar_color);    
        }
    }


    #[On('dark-mode')]
    public function setDarkMode()
    {
        $this->show_sidebar_state = true;
        $current_dark_mode_state = (bool)Cookie::get('dark-mode');
        if(!$current_dark_mode_state) {
            return Cookie::queue('dark-mode', (bool)!$current_dark_mode_state, 525600);
        }
        return Cookie::queue('dark-mode', false, 525600);
    }

    public function showSidebar()
    {
        $this->show_sidebar_state = !$this->show_sidebar_state;
    }

    public function searchPosts()
    {
        $url = url("/?search=$this->postTitle");
        return redirect($url);
    }
    public function render()
    {
        return view('livewire.navbar-component');
    }
}
