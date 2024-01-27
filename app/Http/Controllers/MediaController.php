<?php

namespace App\Http\Controllers;

use App\Http\Requests\CreateLinkMediaRequest;
use App\Http\Requests\MediaCreateRequest;
use App\Http\Requests\MediaUpdateRequest;
use App\Models\Media;
use App\Models\Post;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class MediaController extends Controller
{
    public function create(MediaCreateRequest $request)
    {
        $inputs = $request->validated();
        $file = $inputs['image_file']->storeAs('public/images', $inputs['image_file']->hashName());
        // dd($file);
        // $path = Storage::path($file);
        $path = public_path($file);
        $response = Media::create([
            'post_id' => $inputs['post_id'],
            'name' => $inputs['image_file']->hashName(),
            'file_path' => $path,
            'type' => $inputs['image_file']->extension()
        ]);
        return response()
        ->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'The media file has been successfully uploaded',
            'api_version' => 'v1',
            'data' => $response,
            'error' => null,
        ])
        ->setStatusCode(201);
    }

    public function createLinkMedia(CreateLinkMediaRequest $request)
    {
        $inputs = $request->validated();
        $media_post = Media::where('post_id', $inputs['post_id'])->first();
        if($media_post) {
            $response = Media::where('id', $media_post->id)->update([
                'post_id' => $inputs['post_id'],
                'name' => $inputs['name'],
                'file_path' => $inputs['file_path'],
                'type' => $inputs['type']
            ]);
        }
        if(!$media_post) {
            $response = Media::create([
                'post_id' => $inputs['post_id'],
                'name' => $inputs['name'],
                'file_path' => $inputs['file_path'],
                'type' => $inputs['type']
            ]);
        }
        
        return response()
        ->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'The link media file has been successfully uploaded',
            'api_v ersion' => 'v1',
            'data' => $response,
            'error' => null,
        ])
        ->setStatusCode(201);
    }

    public function update(int $id, MediaUpdateRequest $request)
    {
        $inputs = $request->validated();
        $response = Media::where('post_id', $id)->first();
        Storage::delete('public/images/' . $response->name);
        $file = $inputs['image_file']->storeAs('public/images', $inputs['image_file']->hashName());
        // $path = Storage::path($file);
        $path = public_path('storage/images/' . $inputs['image_file']->hashName());
        $response = Media::where('post_id', $id)->update([
            'name' => $inputs['image_file']->hashName(),
            'file_path' => $path,
            'type' => $inputs['image_file']->extension()
        ]);
        return response()
        ->json([
            'status' => 'success',
            'code' => 201,
            'message' => 'The media file has been successfully updated',
            'api_version' => 'v1',
            'data' => $response,
            'error' => null,
        ])
        ->setStatusCode(201);
    }

    public function getMedia(int $id)
    {
        $image_filename = Media::where('post_id', $id)->first()->name;
        $url_image_file = Storage::path('public/images/' . $image_filename);
        return response()->file($url_image_file);
    }
}
