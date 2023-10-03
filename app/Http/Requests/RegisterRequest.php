<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RegisterRequest extends FormRequest
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
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|email|unique:users',
            'password' => 'required|confirmed|min:8|regex:/^(?=.*[0-9])/'
        ];
    }
        public function messages(): array
    {
        return [
            'password.confirmed' => 'Password and password configurmation fields do not match',
            'password' => 'The password must be at least 8 character long and contain at least one digit',
        ];
    }
 
}
