<?php

namespace App\Http\Controllers;

use App\Http\Requests\CommentCreateRequest;
use App\Http\Resources\CommentByPostResource;
use App\Http\Resources\CommentResource;
use App\Models\Comment;
use App\Models\Post;
use App\Models\User;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function create(CommentCreateRequest $request)
    {
        $inputs = $request->validated();
        $response = Comment::create([
            'post_id' => $inputs['post_id'],
            'user_id' => $inputs['user_id'],
            'parent_id' => $inputs['parent_id'],
            'content' => $inputs['content']
        ]);
        return response()
            ->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'The comment has been successfully created',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ])
            ->setStatusCode(201);
    }

    public function show()
    {
        $response = Comment::all();
        return response()
            ->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Showing all comments',
                'api_version' => 'v1',
                'data' => CommentResource::collection($response),
                'error' => null,
            ])
            ->setStatusCode(200);
    }

    public function showByIdPost(int $id)
    {
        $response = Comment::where('post_id', $id)->get();;
        return response()
            ->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Showing all comments by post',
                'api_version' => 'v1',
                'data' => CommentByPostResource::collection($response),
                'error' => null,
            ])
            ->setStatusCode(200);
    }

}
