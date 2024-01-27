<?php

namespace App\Http\Controllers;

use App\Http\Requests\ApplicationSettingsRequest;
use App\Models\ApplicationSettings;
use Illuminate\Http\Request;

class ApplicationSettingsController extends Controller
{
    public function update(ApplicationSettingsRequest $request)
    {
        $inputs = $request->validated();
        $response = ApplicationSettings::first();
        $response->fill($inputs);
        // $application_settings->navbar_color = empty($inputs['navbar_color']) ? $application_settings->navbar_color : $inputs['navbar_color'];
        // $application_settings->navbar_text_color = empty($inputs['navbar_text_color']) ? $application_settings->navbar_text_color : $inputs['navbar_text_color'];
        // $application_settings->footer_color = empty($inputs['footer_color']) ? $application_settings->footer_color : $inputs['footer_color'];
        // $application_settings->footer_text_color = empty($inputs['footer_text_color']) ? $application_settings->footer_text_color : $inputs['footer_text_color'];
        // $application_settings->logo_filename = empty($inputs['navbar_color']) ? $application_settings->navbar_color : $inputs['navbar_color'];
        $response->save();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The application settings has been successfully updated',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
        );
    }

    public function show()
    {
        $response = ApplicationSettings::first();
        return response()->json(
            [
                'status' => 'success',
                'code' => 200,
                'message' => 'The application settings has been successfully showed',
                'api_version' => 'v1',
                'data' => $response,
                'error' => null,
            ],
        );
    }


}
