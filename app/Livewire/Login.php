<?php

namespace App\Livewire;

use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Hash;
use Livewire\Attributes\Validate;
use Livewire\Component;

class Login extends Component
{
    #[Validate('required|email')]
    public $email;

    #[Validate('required|string')]
    public $password;
    public $remember_me = false;
    public $message;

    public function login()
    {
        $credentials = $this->validate();
        $user = User::where('email', $this->email)->where('role', 'admin')->first();
        if($user && !empty($user->password) && Hash::check($credentials['password'], $user->password)) {
            Auth::login($user, true);
            if((boolean)$this->remember_me) {
                Cookie::queue('admin', auth()->user()->id, 525600); // setahun
            }elseif(!(boolean)$this->remember_me) {
                Cookie::queue('admin', auth()->user()->id, 4320); // 3 hari
            }
            return redirect()->intended('/admin/dashboard')->with('status', 'Login berhasil');
        }
        return session()->now('message', 'Email atau password salah');
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
