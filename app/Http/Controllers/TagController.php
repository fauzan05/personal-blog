<?php

namespace App\Http\Controllers;

use App\Http\Requests\TagCreateRequest;
use App\Http\Requests\TagUpdateRequest;
use App\Http\Resources\TagResource;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagController extends Controller
{
    public function create(TagCreateRequest $request)
    {
        $input = $request->validated();
        $response = Tag::create([
            'name' => $input['name'],
        ]);
        return response()
            ->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'The tag has been successfully created',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ])
            ->setStatusCode(201);
    }

    public function update(int $id, TagUpdateRequest $request)
    {
        $input = $request->validated();
        $response = Tag::find($id);
        $response->name = $input['update_name'];
        $response->save();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'The tag has been successfully updated',
            'api_version' => 'v1',
            'data' => $response,
            'error' => null,
        ]);
    }

    public function show()
    {
        $responses = Tag::all();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'Showing all of tags',
                'api_version' => 'v1',
                'data' =>  TagResource::collection($responses),
                'error' => null,
            ],
        );
    }

    public function delete(int $id)
    {
        Tag::find($id)->delete();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'The tag has been successfully deleted',
            'api_version' => 'v1',
            'data' => null,
            'error' => null,
        ]);
    }

    public function destroy()
    {
        $tags = Tag::all();
        foreach ($tags as $key => $tag):
            $tag->delete();
        endforeach;
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'The tags has been successfully destroyed',
            'api_version' => 'v1',
            'data' => null,
            'error' => null,
        ]);
    }
}
