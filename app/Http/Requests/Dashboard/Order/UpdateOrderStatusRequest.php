<?php

namespace App\Http\Requests\Dashboard\Order;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateOrderStatusRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'status' => [
                'required',
                Rule::in([
                    'pending',
                    'processing',
                    'completed',
                    'cancelled',
                ]),
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'status.required' => 'حالة الطلب مطلوبة.',

            'status.in' => 'حالة الطلب غير صحيحة.',
        ];
    }
}
