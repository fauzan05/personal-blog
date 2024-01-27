<?php

namespace App\Http\Controllers;

use App\Http\Requests\CategoryCreateRequest;
use App\Http\Requests\CategoryUpdateRequest;
use App\Http\Resources\CategoryResource;
use App\Models\Category;
use Illuminate\Http\Request;

class CategoryController extends Controller
{
    public function create(CategoryCreateRequest $request)
    {
        $inputs = $request->validated();
        $response = Category::create([
            'name' => $inputs['name'],
            'description' => $inputs['description']
        ]);
        return response()->json(
            [
                'status' => 'success',
                'code' => 201,
                'message' => 'The category has been successfully created',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
        )->setStatusCode(201);
    }

    public function update(int $id, CategoryUpdateRequest $request)
    {
        $inputs = $request->validated();
        $response = Category::find($id);
        $response->name = !$inputs['update_name'] ? $response->name : $inputs['update_name'];
        $response->description = $inputs['description'];
        $response->save();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'The category has been successfully updated',
            'api_version' => 'v1',
            'data' => $response,
            'error' => null,
        ]);
    }

    public function show()
    {
        $response = Category::all();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'Showing all of categories',
                'api_version' => 'v1',
                'data' => CategoryResource::collection($response),
                'error' => null,
            ],
        );
    }

    public function delete(int $id)
    {
        Category::find($id)->delete();
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'The category has been successfully deleted',
            'api_version' => 'v1',
            'data' => null,
            'error' => null,
        ]);
    }

    public function destroy()
    {
        $categories = Category::all();
        foreach ($categories as $category):
            $category->delete();
        endforeach;
        return response()->json([
            'status' => 'success',
            'code' => 200,
            'message' => 'The categories has been successfully destroyed',
            'api_version' => 'v1',
            'data' => null,
            'error' => null,
        ]);
    }
}
