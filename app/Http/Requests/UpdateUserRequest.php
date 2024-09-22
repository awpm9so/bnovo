<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class UpdateUserRequest extends FormRequest
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
            'name' => 'string|max:30',
            'last_name' => 'string|max:30',
            'email' => 'string|email|max:255|unique:users',
            'phone' => 'regex:/^[0-9]+ [0-9]+$/|string|max:25|unique:users',
            'country' => 'string|max:60',
        ];
    }
}
