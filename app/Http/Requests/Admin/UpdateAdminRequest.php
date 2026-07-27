<?php

namespace App\Http\Requests\Admin;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateAdminRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        $adminId = $this->route('id');

        return [

            'name' => 'required|string|max:255',

            'email' => [
                'required',
                'email',
                Rule::unique('users')->ignore($adminId),
            ],

            'phone' => [
                'nullable',
                Rule::unique('users')->ignore($adminId),
            ],

            'password' => 'nullable|string|min:8|confirmed',

            'image' => 'nullable|image|max:2048',

            'status' => 'required|boolean',
        ];
    }
}
