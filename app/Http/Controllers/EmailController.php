<?php

namespace App\Http\Controllers;

use App\Mail\ForgotPasswordMail;
use App\Models\User;
use Illuminate\Support\Facades\Mail;


class EmailController extends Controller
{
    public static function sendVerificationCode(string $recipient_email)
    {
        $verification_code = User::select('verification_code')->where('role', 'admin')->first()->verification_code;
        $title = "Kode Verifikasi Reset Password";
        $body = "Kode verifikasi anda adalah $verification_code";
        Mail::to($recipient_email)->send(new ForgotPasswordMail($title, $body));
        return response()->json(['message' => "Kode Verifikasi Berhasil Dikirim Ke Email $recipient_email"]);
    }
}
