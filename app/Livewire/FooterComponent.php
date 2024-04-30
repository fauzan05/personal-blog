<?php

namespace App\Livewire;

use App\Models\Address;
use App\Models\ApplicationSettings;
use App\Models\Menu;
use App\Models\SocialMedia;
use Livewire\Component;

class FooterComponent extends Component
{
    public $logo;
    public $footer_color;
    public $footer_text_color;
    public $blog_name;
    public $menus;
    public $phone_number;
    public $email;
    public $main_address;
    public $social_medias;
    public $application_settings;
    public $addresses;

    public function mount()
    {
        $this->getAllData();
    }

    public function getAllData()
    {
        $this->menus = Menu::all();
        $this->application_settings = ApplicationSettings::first();
        $this->addresses = Address::all()->toArray();
        $this->social_medias = SocialMedia::all();
        $this->logo = $this->application_settings->logo_filename ?? "Untitled";
        $this->phone_number = $this->application_settings->phone_number ?? "081234567890";
        $this->email = $this->application_settings->email ?? "Example@mail.com";
        $this->footer_color = $this->application_settings->footer_color ?? null;
        $this->footer_text_color = $this->application_settings->footer_text_color ?? null;
        $this->blog_name = $this->application_settings->blog_name ?? "Untitled";
        
        if(!empty($this->addresses)) {
            $this->main_address = array_filter($this->addresses, function($main_address) {
                return (boolean)$main_address['is_active'] === true;
            });
        }
        if($this->footer_color && $this->footer_text_color) {
            $this->dispatch('footer-text-color', data: $this->footer_text_color);
            $this->dispatch('footer-color', data: $this->footer_color); 
        }
       
    }

    public function render()
    {
        return view('livewire.footer-component');
    }
}
