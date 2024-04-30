<?php

namespace App\Livewire;

use App\Http\Controllers\EmailController;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Livewire\Attributes\On;
use Livewire\Component;

class ForgotPassword extends Component
{
    public $current_email;
    public $verification_code;
    public $response;
    public $button_state = false;
    public $change_password_state;
    public $new_password;
    public $new_password_confirmation;
    public function mount()
    {
        $this->getCurrentEmail();
        // dd($this->current_email);
        $this->sendEmail();
    }

    public function getCurrentEmail()
    {
        $this->current_email = User::select('email')
            ->where('role', 'admin')->first()->email ?? 'example@mail.com';
    }

    public function sendEmail()
    {
        $verification_code = random_int(100000, 999999);
        User::where('role', 'admin')->update([
            'verification_code' => $verification_code
        ]);
        EmailController::sendVerificationCode($this->current_email);
        // $this->response = $response->getData()->message;
        // session()->now('message', $this->response);
    }

    #[On('enable-button')]
    public function setButtonState($buttonState)
    {
        $this->button_state = (bool)$buttonState;
        if (!$buttonState) {
            $this->sendEmail();
            $this->dispatch('disable-button', data: false);
        }
    }

    public function verifyCode()
    {
        // dd($this->verification_code);
        $current_verification_code = User::select('verification_code')
            ->where('role', 'admin')
            ->first();
        if((integer)$this->verification_code === (integer)$current_verification_code->verification_code) {
            return $this->change_password_state = true;
        }
        return session()->now('message', 'Kode verifikasi yang anda masukkan tidak valid/salah!');
    }

    public function updatePassword()
    {
        Validator::make(
            ['new_password' => $this->new_password, 'new_password_confirmation' => $this->new_password_confirmation],
            ['new_password' => 'required|min:3|max:50|string', 'new_password_confirmation' => 'required|min:3|max:50|same:new_password']
        )->validated();
        User::where('role', 'admin')->update([
            'password' => Hash::make($this->new_password)
        ]);
        return redirect('/login-admin');
    }

    public function render()
    {
        return view('livewire.forgot-password');
    }
}
