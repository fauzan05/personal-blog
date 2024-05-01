<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function login()
    {
        return view('users.login');
    }

    public function profile(Request $request)
    {
        $search = $request->query('search', null);
        if(!$search) {
            $image = User::select('profile_photo_filename')->first();
            return view('app.index', ['image_profile' => $image->profile_photo_filename ?? ""]);
        }
        // jika search ada, maka cari postingan
        return view('app.search', ['current_search_keyword' => trim($search)]);
  
    }

    public function logout()
    {
        Auth::logout();
        return redirect('/login-admin')->with('status', 'Sesi anda telah habis! Silahkan login kembali');
    }
}
