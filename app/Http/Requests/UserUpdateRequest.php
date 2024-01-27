<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdateRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'first_name' => ['nullable','string','max:50'],
            'last_name' => ['nullable','string','max:50'],
            'username' => ['nullable','string','max:50'],
            'email' => ['nullable','string','max:50'],
            'old_password' => ['required','string','max:50'],
            'new_password' => ['nullable','string','max:50'],
            'new_password_confirmation' => ['nullable','string','max:50', 'same:new_password'],
            'place_of_birth' => ['nullable','string','max:50'],
            'date_of_birth' => ['nullable','date','max:50'],
            'phone_number' => ['nullable','string','max:50'],
            'bio' => ['nullable', 'string'],
            'profile_photo_filename' => ['nullable', 'string']
        ];
    }

    protected function failedValidation(\Illuminate\Contracts\Validation\Validator $validator)
    {
        throw new HttpResponseException(response([
            'status' => 'failed',
            'code' => 400,
            'message' => 'Validation Error',
            'api_version' => 'v1',
            'data' => null,
            'error' => [
                'error_message' => $validator->getMessageBag()
            ]       
        ], 400));
    }
}
