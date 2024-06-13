<?php

namespace App\Http\Controllers;

use App\Models\ApplicationSettings;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public $logo_filename;

    public function __construct()
    {
        $this->logo_filename = ApplicationSettings::select('logo_filename')->first()->logo_filename;
    }
    public function login()
    {
        return view('users.login', ['logo_filename' => $this->logo_filename]);
    }

    public function profile(Request $request)
    {
        $search = $request->query('search', null);
        if(!$search) {
            $image = User::select('profile_photo_filename')->first();
            return view('app.index', ['image_profile' => $image->profile_photo_filename ?? "", 'logo_filename' => $this->logo_filename]);
        }
        // jika search ada, maka cari postingan
        return view('app.search', ['current_search_keyword' => trim($search), 'logo_filename' => $this->logo_filename]);
  
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login-admin')->with('status', 'Sesi anda telah habis! Silahkan login kembali');
    }
}
