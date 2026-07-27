<?php

namespace App\Http\Requests\Api\Prescription;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StorePrescriptionRequest extends FormRequest
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
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'image' => [
                'required', 
                'image',
                'mimes:jpg,jpeg,png,webp',
                'max:5120',
            ],
        ];
    }

    /**
     * Custom validation messages.
     */
    public function messages(): array
    {
        return [
            'image.required' => 'صورة الروشتة مطلوبة.',
            'image.image' => 'الملف المرفوع يجب أن يكون صورة.',
            'image.mimes' => 'يجب أن تكون صورة الروشتة بصيغة JPG أو JPEG أو PNG أو WEBP.',
            'image.max' => 'يجب ألا يتجاوز حجم صورة الروشتة 5 ميجابايت.',
        ];
    }
}
