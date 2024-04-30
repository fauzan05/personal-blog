<?php

namespace App\Livewire;

use App\Models\ApplicationSettings;
use Livewire\Component;

class AdminAbout extends Component
{
    public $api_address;
    public $token;
    public $headers;
    public $app_version;
    public function mount()
    {
        $this->getAppSettings();
    }

    public function getAppSettings()
    {
        $this->app_version = ApplicationSettings::first()->app_version ?? "1.0.0";
    }
    public function render()
    {
        return view('livewire.admin-about');
    }
}
