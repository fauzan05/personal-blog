<?php

use Illuminate\Support\Facades\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;

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

Route::get('/not-found', function () {
    return view('notfound');
});
Route::get('/login-admin', [AuthController::class, 'login'])->name('login');
Route::middleware('auth')->group(function () {
    Route::get('/admin/dashboard', function () {
        return view('dashboards.admin.home');
    });
    Route::get('/admin/posts', function () {
        return view('dashboards.admin.post');
    });
    Route::get('/admin/settings', function () {
        return view('dashboards.admin.setting');
    });
    Route::get('/admin/about', function () {
        return view('dashboards.admin.about');
    });
    // Route::post('/logout', [AuthController::class, 'logout']);
});

Route::get('/', function () {
    return view('app.index');
});
// Route::get('/{mainpath}/{secondpath?}', function (string $mainpath, string $secondpath = null) {
//     if (trim(strtolower($mainpath)) === 'about') {
//         return view('app.about');
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




Route::post('image-upload', function (Request $request) {
    if ($request->hasFile('upload')) {
        $originName = $request->file('upload')->hashName();
        $fileName = pathinfo($originName, PATHINFO_FILENAME);
        $extension = $request->file('upload')->extension();
        $fileName = $fileName . '.' . $extension;
        $request->file('upload')->move(public_path('temp'), $fileName);
        $url = asset('temp/' . $fileName);
        return response()->json(['fileName' => $fileName, 'uploaded' => 1, 'url' => $url]);
    }
})->name('ckeditor.upload');
