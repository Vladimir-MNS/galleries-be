<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreGalleryRequest extends FormRequest
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
            'name' => 'required|min:2|max:255',
            'description' => 'max:1500', 
            'user_id' => 'required|exists:users,id',
            'images' => [
                'required',
                'array', 
                'min:1'
            ]
        ];


    }

    public function messages(): array {
        return [
            'images' => 'You have to add at least one image'
        ];
    }
}
