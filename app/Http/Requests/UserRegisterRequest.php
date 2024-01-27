<?php

namespace App\Http\Requests;

use App\Enum\UserRoleEnum;
use Illuminate\Foundation\Http\FormRequest;
use App\Enums\ServerStatus;
use Illuminate\Http\Exceptions\HttpResponseException;
use Illuminate\Validation\Rule;

class UserRegisterRequest extends FormRequest
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
            'first_name' => ['required','string','max:50'],
            'last_name' => ['required','string','max:50'],
            'username' => ['required','string','max:50'],
            'email' => ['required','string','max:50'],
            'password' => ['required','string','max:50'],
            'password_confirmation' => ['required','string','max:50', 'same:password'],
            'place_of_birth' => ['required','string','max:50'],
            'date_of_birth' => ['required','date','max:50'],
            'phone_number' => ['required','string','max:50'],
            'role' => [Rule::enum(UserRoleEnum::class)],
            'bio' => ['nullable', 'string']
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
