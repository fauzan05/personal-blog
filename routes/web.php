<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\MenuController;
use App\Models\ApplicationSettings;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

// Route::get('/', function () {
//     return view('welcome');
// });
Route::post('image-upload', function (Request $request) {
    // upload adalah nama default dari kolom data yang diberikan oleh CKEditor
    if ($request->hasFile('upload')) {
        $originName = $request->file('upload')->hashName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $request->file('upload')->extension();
        $fileName = $fileName . '.' . $extension;
        $request->file('upload')->move(public_path('temp'), $fileName);
        // $request->file('upload')->move(__DIR__ . '/../../Zoom', $fileName);
        $url = asset('temp/' . $fileName);
        return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url], Response::HTTP_OK);
    }
})->name('ckeditor.upload');

Route::get('/login-admin', [AuthController::class, 'login'])->name('login');
// Jika tabel belum di migrate, sebaiknya komentar semua blok kode dari sini karena error tabel application_settings tidak ditemukan
Route::middleware('auth')->group(function () {
    $logo_filename = ApplicationSettings::select('logo_filename')->first()->logo_filename ?? "";
    Route::get('/admin/dashboard', function () use($logo_filename) {
        return view('dashboards.admin.home', ['logo_filename' => $logo_filename]);
    });
    Route::get('/admin/posts', function () use($logo_filename) {
        return view('dashboards.admin.post', ['logo_filename' => $logo_filename]);
    });
    Route::get('/admin/settings', function () use($logo_filename) {
        return view('dashboards.admin.setting', ['logo_filename' => $logo_filename]);
    });
    Route::get('/admin/about', function () use($logo_filename) {
        return view('dashboards.admin.about', ['logo_filename' => $logo_filename]);
    });
    Route::get('/admin/logout', function (Request $request) {
        Auth::logout();
        Cookie::expire('admin');
        return redirect('/login-admin')->with('status', 'Sesi anda telah habis! Silahkan login kembali');
    });
});

Route::get('/forgot-password', function () {
    $logo_filename = ApplicationSettings::select('logo_filename')->first()->logo_filename ?? "";
    return view('app.forgot-password', ['logo_filename', $logo_filename]);
});

// Route::get('/{mainpath}/{secondpath?}', function (string $mainpath, string $secondpath = null) {
//     if (trim(strtolower($mainpath)) === 'about') {
//         return view('app.about');
//     }
//     if($mainpath && !$secondpath) {
//         dd(true);
//     }
//     // if (empty(trim(strtolower($secondpath))) && !empty(trim(strtolower($mainpath)))) {
//     //     $response = Http::get(config('services.api_address') . "menu/$mainpath");
//     //     if($response->notFound()) {
//     //         return view('notfound');
//     //     }
//     //     $response = json_decode($response->body(), JSON_OBJECT_AS_ARRAY);
//     //     // Jika bukan konten maka
//     //     if(!$response['is_content']) {
//     //         return view('app.home', ['main_path' => $mainpath]);
//     //     }

//     //     return view('app.content', ['content' => $response['data']['data'][0], 'title' => $response['data']['data'][0]['title']]);
//     // }
//     if (!empty(trim(strtolower($secondpath))) && !empty(trim(strtolower($mainpath)))) {
//         return view('app.categories', ['main_path' => $mainpath, 'second_path' => $secondpath]);
//     }

//     return view('app.home', ['main_path' => $mainpath]);
// });


Route::get('/{mainpath}/{secondpath?}/{thirdpath?}/{fourthpath?}', [MenuController::class, 'redirectTo']);
Route::get('/', [AuthController::class, 'profile'])->name('profile');
