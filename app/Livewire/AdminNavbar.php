<?php

namespace App\Livewire;

use App\Models\ApplicationSettings;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Request;
use Livewire\Attributes\On;
use Livewire\Component;

class AdminNavbar extends Component
{
    public $darkModeState;
    public $navbar_color;
    public $navbar_text_color;
    public $footer_color;
    public $footer_text_color;
    public $logo;
    public $current_url;
    public function mount()
    {
        (bool)$this->darkModeState = (bool)Cookie::get('dark-mode') ? true : false;
        $this->getAllConfigNavbar();
        $current_url = Request::url();
        $parseUrl = parse_url($current_url);
        if(!empty($parseUrl['path'])) {
            $pathSegments = explode('/', $parseUrl['path']);
            $this->current_url = $pathSegments[1] . "/$pathSegments[2]" ;
        }
    }

    public function getAllConfigNavbar()
    {
        $response = ApplicationSettings::first();
        // dd($response);
        if(!empty($response)) {
            $response->toArray();
        }
        $this->navbar_color = $response['navbar_color'] ?? "var(--main-color)";
        $this->navbar_text_color = $response['navbar_text_color'] ?? "var(--text-color)";
        $this->logo = $response['logo_filename'] ?? "Untitled";
        $this->footer_color = $response['footer_color'] ?? "var(--main-color)";
        $this->footer_text_color = $response['footer_text_color'] ?? "var(--text-color)";
        $this->dispatch('navbar-text-color', data: $this->navbar_text_color);
        $this->dispatch('navbar-color', data: $this->navbar_color);
        $this->dispatch('footer-color', data: $this->footer_color);
        $this->dispatch('footer-text-color', data: $this->footer_text_color);
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login-admin')->with('status', 'Sesi anda telah habis! Silahkan login kembali');
    }

    #[On('dark-mode')]
    public function setDarkMode()
    {
        Cookie::queue('dark-mode', (bool)!$this->darkModeState);
        $this->darkModeState = !$this->darkModeState;
    }
    public function render()
    {
        return view('livewire.admin-navbar');
    }
}
