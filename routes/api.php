<?php

use App\Http\Controllers\AddressController;
use App\Http\Controllers\ApplicationSettingsController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\CommentController;
use App\Http\Controllers\MediaController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\PostController;
use App\Http\Controllers\SocialMediaController;
use App\Http\Controllers\TagController;
use App\Models\ApplicationSettings;
use App\Models\Media;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('/register/guest', [AuthController::class, 'registerGuest']);
Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login'])->middleware('user.login');

Route::middleware('auth:api')->group(function () {
    // Auth
    Route::get('/info', [AuthController::class, 'get']);
    Route::patch('/update', [AuthController::class, 'update'])->middleware(['email.exist', 'old.password']);
    Route::patch('/update-password', [AuthController::class, 'updatePassword'])->middleware('old.password');
    Route::delete('/logout', [AuthController::class, 'logout']);
    Route::delete('/destroy', [AuthController::class, 'destroy'])->middleware('old.password');
    // Address
    Route::post('/address', [AddressController::class, 'create'])->middleware('user.id.exist');
    Route::patch('/address/{idAddress}', [AddressController::class, 'update'])->middleware(['user.id.exist', 'address.exist'])->where('idAddress', '[0-9]+');
    Route::delete('/address/{idAddress}', [AddressController::class, 'delete'])->middleware(['user.id.exist', 'address.exist'])->where('idAddress', '[0-9]+');
    // Social Media
    Route::post('/social-media', [SocialMediaController::class, 'create'])->middleware('user.id.exist');
    Route::patch('/social-media/{idSocialMedia}', [SocialMediaController::class, 'update'])->middleware('user.id.exist')->where('idSocialMedia', '[0-9]+');
    Route::delete('/social-media/{idSocialMedia}', [SocialMediaController::class, 'delete'])->middleware(['user.id.exist', 'social.media.is.exist'])->where('isSocialMedia', '[0-9]+');
    // Tag
    Route::post('/tag', [TagController::class, 'create'])->middleware('tag.exist');
    Route::patch('/tag/{idTag}', [TagController::class, 'update'])->middleware('tag.exist')->where('idTag', '[0-9]+');
    Route::get('/tag', [TagController::class, 'show']);
    Route::delete('/tag/{idTag}', [TagController::class, 'delete'])->middleware('tag.exist')->where('idTag', '[0-9]+');
    Route::delete('/tag', [TagController::class, 'destroy'])->middleware('tag.exist');
    // Category
    Route::post('/category', [CategoryController::class, 'create'])->middleware('category.exist');
    Route::patch('/category/{idCategory}', [CategoryController::class, 'update'])->middleware('category.exist')->where('idCategory', '[0-9]+');
    Route::delete('/category/{idCategory}', [CategoryController::class, 'delete'])->middleware('category.exist')->where('idCategory', '[0-9]+');
    Route::delete('/category', [CategoryController::class, 'destroy']);
    // Post
    Route::post('/post', [PostController::class, 'create'])->middleware('post.exist');
    Route::patch('/post/{idPost}', [PostController::class, 'update'])->middleware('post.exist')->where('idPost', '[0-9]+');
    Route::delete('/post/{idPost}', [PostController::class, 'delete'])->where('idPost', '[0-9]+');
    Route::delete('/post', [PostController::class, 'destroy']);

    // Application Settings
    // Route::post('/application-settings', [ApplicationSettingsController::class, 'create']);
    Route::patch('/application-settings', [ApplicationSettingsController::class, 'update']);

    // Menu
    Route::post('/menu', [MenuController::class, 'create'])->middleware('menu.exist');
    Route::patch('/menu/{idMenu}', [MenuController::class, 'update'])->middleware('menu.exist')->where('idMenu', '[0-9]+');
    Route::delete('/menu/{idMenu}', [MenuController::class, 'delete'])->where('idMenu', '[0-9]+');
});

// Comment
Route::post('/comment', [CommentController::class, 'create']);
Route::get('/comment/post/{idPost}', [CommentController::class, 'showByIdPost'])->middleware('post.exist')->where('idPost', '[0-9]+');
Route::get('/comment', [CommentController::class, 'show']);

// Media
Route::post('/media', [MediaController::class, 'create']);
Route::post('/media-link', [MediaController::class, 'createLinkMedia']);
Route::post('/media/post/{idPost}', [MediaController::class, 'update'])->middleware('post.exist')->where('idPost', '[0-9]+');
Route::get('/media/post/{idPost}', [MediaController::class, 'getMedia'])->middleware(['post.exist', 'media.exist'])->where('idPost', '[0-9]+');

// Post
Route::get('/post/search/{title}', [PostController::class, 'search']);
Route::get('/post', [PostController::class, 'show']);

// Application
Route::get('/application-settings', [ApplicationSettingsController::class, 'show']);
// Menu
Route::get('/menu', [MenuController::class, 'show']);
Route::get('/menu/{menu}/{category?}', [MenuController::class, 'showPost'])->middleware(['menu.exist', 'category.exist']);

// Social Media
Route::get('/social-media', [SocialMediaController::class, 'get'])->middleware('user.id.exist');
Route::get('/address', [AddressController::class, 'get']);

// Category
Route::get('/category', [CategoryController::class, 'show']);
