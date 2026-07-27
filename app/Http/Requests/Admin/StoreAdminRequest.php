<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;

class StoreAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

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
            'email.email' => 'Invalid email.',
            'email.unique' => 'Email already exists.',

            'phone.unique' => 'Phone already exists.',

            'password.required' => 'Password is required.',
            'password.min' => 'Password must be at least 8 characters.',
            'password.confirmed' => 'Password confirmation does not match.',

            'image.image' => 'Image must be valid.',
            'image.max' => 'Image size must not exceed 2MB.',
        ];
    }
}
