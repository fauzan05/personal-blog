<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Requests\PostCreateRequest;
use App\Http\Requests\PostUpdateRequest;
use App\Http\Requests\SearchPostRequest;
use App\Http\Resources\PostResource;
use App\Http\Resources\PostResourceCollection;
use App\Models\Post;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class PostController extends Controller
{
    public function create(PostCreateRequest $request)
    {
        $inputs = $request->validated();
        // dd($inputs['tag_id']);
        $response = Post::create([
            'user_id' => $inputs['user_id'],
            'category_id' => $inputs['category_id'],
            'title' => $inputs['title'],
            'content' => $inputs['content'],
            'location' => $inputs['location'],
            'menu_id' => $inputs['menu_id'],
            'slug' => Str::of($inputs['title'])->slug('-')
        ]);

        // dd($response->id);
        foreach ($inputs['tag_id'] as $tag) :
            $posts_tags = DB::table('post_tag')->insert([
                'post_id' => $response->id,
                'tag_id' => $tag,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
            $posts_tags = DB::table('post_tag')
                ->where('post_id', $response->id)
                ->get();
        endforeach;
        return response()
            ->json([
                'status' => 'success',
                'code' => 201,
                'message' => 'The post has been successfully created',
                'api_version' => 'v1',
                'data' => [$response, $posts_tags],
                'error' => null,
            ])
            ->setStatusCode(201);
    }

    public function update(int $id, PostUpdateRequest $request)
    {
        $inputs = $request->validated();
        $response = Post::find($id);
        $response->category_id = !$inputs['category_id'] ? $response->category_id : $inputs['category_id'];
        $response->title = !$inputs['title'] ? $response->title : $inputs['title'];
        $response->content = !$inputs['content'] ? $response->content : $inputs['content'];
        $response->location = !$inputs['location'] ? $response->location : $inputs['location'];
        $response->menu_id = !$inputs['menu_id'] ? $response->menu_id : $inputs['menu_id'];
        $response->slug = !$inputs['title'] ? $response->slug : Str::of($inputs['title'])->slug('-');
        $response->updated_at = Carbon::now();
        $response->save();

        $tags = DB::table('post_tag')
            ->select('id')
            ->where('post_id', $id)
            ->get();
        foreach ($tags as $tag) :
            DB::table('post_tag')
                ->where('id', $tag->{'id'})
                ->delete();
        endforeach;

        foreach ($inputs['tag_id'] as $tag) :
            $posts_tags = DB::table('post_tag')->insert([
                'post_id' => $id,
                'tag_id' => $tag,
                'created_at' => Carbon::now(),
                'updated_at' => Carbon::now(),
            ]);
        endforeach;
        $posts_tags = DB::table('post_tag')
            ->where('post_id', $id)
            ->get();
        return response()
            ->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'The post has been successfully updated',
                'api_version' => 'v1',
                'data' => [$response, $posts_tags],
                'error' => null,
            ])
            ->setStatusCode(200);
    }

    public function show()
    {
        $response = Post::all();
        return response()
            ->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Showing all posts',
                'api_version' => 'v1',
                'data' => PostResource::collection($response),
                'error' => null,
            ])
            ->setStatusCode(200);
    }

    public function search(string $title)
    {
        
        $response = Post::where('title', 'like', '%' . $title . '%')->get();
;
        return response()
            ->json([
                'status' => 'success',
                'code' => 200,
                'message' => 'Showing result of all posts keywords',
                'api_version' => 'v1',
                'data' => PostResource::collection($response),
                'error' => null,
            ])
            ->setStatusCode(200);
    }

    public function delete(int $id)
    {
        Post::find($id)->delete();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'The post has been successfully deleted',
            'api_version' => 'v1',
            'data' => null,
            'error' => null,
        ]);
    }

    public function destroy()
    {
        $posts = Post::all();
        foreach ($posts as $post):
            $post->delete();
        endforeach;
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'The posts has been successfully destroyed',
            'api_version' => 'v1',
            'data' => null,
            'error' => null,
        ]);
    }


}
