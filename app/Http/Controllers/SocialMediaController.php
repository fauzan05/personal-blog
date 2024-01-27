<?php

namespace App\Http\Controllers;

use App\Http\Requests\SocialMediaCreateRequest;
use App\Http\Requests\SocialMediaUpdateRequest;
use App\Models\SocialMedia;
use App\Models\User;
use Illuminate\Http\Request;

class SocialMediaController extends Controller
{
    public function create(SocialMediaCreateRequest $request)
    {
        $inputs = $request->validated();
        $response = SocialMedia::create([
            'user_id' => $inputs['user_id'],
            'account_name' => $inputs['account_name'],
            'account_link' => $inputs['account_link'],
            'type' => $inputs['type'],
            'access_token' => $inputs['access_token'],
            'additional_information' => $inputs['additional_information']
        ]);
        return response()->json([
            [
                'status' => 'success',
                'code' => 201,
                'message' => 'The social media has been successfully created',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
        ])->setStatusCode(201);
    }

    public function update(int $id, SocialMediaUpdateRequest $request)
    {
        $inputs = $request->validated();
        $response = SocialMedia::find($id);
        $response->account_name = empty(trim($inputs['account_name'])) ? $response->account_name : $inputs['account_name'];
        $response->account_link = empty(trim($inputs['account_link'])) ? $response->account_link : $inputs['account_link'];
        $response->type = empty($inputs['type']) ? $response->type : $inputs['type'];
        $response->access_token = null;
        $response->additional_information = null;
        $response->save();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The social media has been successfully updated',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
        );
    }

    public function get()
    {
        $admin = User::where('role', 'admin')->first();
        $response = SocialMedia::where('user_id', $admin->id)->get();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The information of social media by current user',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
        );
    }

    public function delete(int $id)
    {
        $response = SocialMedia::find($id)->delete();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The social media has been successfully deleted',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
        );
    }    

}
