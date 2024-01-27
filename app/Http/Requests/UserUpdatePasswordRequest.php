<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class UserUpdatePasswordRequest extends FormRequest
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
            'old_password' => ['required', 'string', 'min:3', 'max:50'],
            'new_password' => ['required', 'string', 'min:3', 'max:50'],
            'new_password_confirmation' => ['required', 'string', 'min:3', 'max:50', 'same:new_password'],
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
