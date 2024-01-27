<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Session;
use Livewire\Attributes\Validate;
use Livewire\Component;
use Livewire\Attributes\On;

class Login extends Component
{
    #[Validate('required|email')]
    public $email;

    #[Validate('required|string')]
    public $password;
    public $remember_me = false;
    public $message;
    public $darkModeState;

    public function login()
    {
        $this->validate();
        
        $user = User::where('email', $this->email)->first();

        $response = Auth::attempt([
            "email" => $this->email,
            "password" => $this->password
        ]);
        if(!$user || !Hash::check($this->password, $user->password)) {
            return session()->now('message', 'Email atau password salah');
        }
        Session::regenerate();
       
        if(!isset($_COOKIE['dark-mode'])) {
            Cookie::queue('dark-mode', (boolean)false);
        }
        return redirect('/admin/dashboard')->with('status', 'Login berhasil');
    }
   

    #[On('dark-mode')]
    public function setDarkModeState()
    {
        Cookie::queue('dark-mode', (boolean)!$this->darkModeState);
        $this->darkModeState = !$this->darkModeState;
    }

    public function loginButton()
    {
        return; // agar loading sesuai target, maka dibuat function kosongan
    }
    public function render()
    {
        return view('livewire.login');
    }
}
