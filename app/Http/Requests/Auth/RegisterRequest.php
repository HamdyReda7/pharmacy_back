<?php

namespace App\Http\Requests\Auth;

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

            'name' => 'required|string|max:255',

            'email' => 'required|email|max:255|unique:users,email',

            'phone' => 'nullable|string|max:20|unique:users,phone',

            'password' => 'required|string|min:8|confirmed',

            'image' => 'nullable|image|max:2048',

        ];
    }

    public function messages(): array
    {
        return [

            'name.required' => 'Name is required.',

            'email.required' => 'Email is required.',
            'email.email' => 'Invalid email address.',
            'email.unique' => 'This email already exists.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'image.image' => 'Image must be a valid image.',
            'image.max' => 'Image size must not exceed 2MB.',

            'phone.required' => 'Phone is required.',
            'phone.unique'   => 'This phone already exists.',
            'phone.max'      => 'Phone must not exceed 20 characters.',

        ];
    }
}
