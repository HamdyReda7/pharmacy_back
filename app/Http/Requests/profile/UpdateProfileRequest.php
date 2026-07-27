<?php

namespace App\Http\Requests\Profile;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProfileRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [

            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user()->id),
            ],

            'phone' => [
                'nullable',
                'string',
                'max:20',
                Rule::unique('users', 'phone')->ignore($this->user()->id),
            ],

            'image' => 'nullable|image|max:2048',

        ];
    }

    public function messages(): array
    {
        return [

            'name.required' => 'Name is required.',
            'name.max' => 'Name must not exceed 255 characters.',

            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email address.',
            'email.max' => 'Email must not exceed 255 characters.',
            'email.unique' => 'This email already exists.',

            'phone.max' => 'Phone must not exceed 20 characters.',
            'phone.unique' => 'This phone already exists.',

            'image.image' => 'Image must be a valid image.',
            'image.mimes' => 'Allowed image types: jpg, jpeg, png, webp.',
            'image.max' => 'Image size must not exceed 2MB.',

        ];
    }
}
