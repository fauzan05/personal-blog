<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class AddressCreateRequest extends FormRequest
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
            'user_id' => ['required', 'integer', 'max:50'],
            // 'street' => ['nullable', 'string', 'max:100'],
            // 'village' => ['nullable', 'string', 'max:50'],
            // 'subdistrict' => ['nullable', 'string', 'max:50'],
            // 'city' => ['required', 'string', 'max:50'],
            'province' => ['required', 'string', 'max:50'],
            'country' => ['required', 'string', 'max:50'],
            // 'postal_code' => ['required', 'string', 'max:10'],
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
