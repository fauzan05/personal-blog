<?php

namespace App\Livewire;

use App\Models\ApplicationSettings;
use App\Models\Menu;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;
use Livewire\Component;

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
        $this->navbar_color = $this->application_settings->navbar_color ?? "Untitled";
        $this->navbar_text_color = $this->application_settings->navbar_text_color ?? "Untitled";
        $this->dispatch('navbar-text-color', data: $this->navbar_text_color);
        $this->dispatch('navbar-color', data: $this->navbar_color);    
    }

    #[On('dark-mode')]
    public function setDarkMode()
    {
        Cookie::queue('dark-mode', (bool)!$this->darkModeState);
        $this->darkModeState = !$this->darkModeState;
    }
    public function render()
    {
        return view('livewire.navbar-component');
    }
}
