<?php

namespace App\Http\Requests\Api\Order;

use Illuminate\Foundation\Http\FormRequest;

class StoreOrderRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }

    public function rules(): array
    {
        return [
            'name' => ['required', 'string', 'max:255'],

            'phone' => ['required', 'string', 'max:20'],

            'governorate' => ['required', 'string', 'max:255'],

            'address' => ['required', 'string', 'max:1000'],

            'notes' => ['nullable', 'string', 'max:1000'],

            'items' => ['required', 'array', 'min:1'],

            'items.*.product_id' => [
                'required',
                'exists:products,id',
            ],

            'items.*.quantity' => [
                'required',
                'integer',
                'min:1',
            ],
        ];
    }

    public function messages(): array
    {
        return [
            'name.required' => 'الاسم مطلوب.',

            'phone.required' => 'رقم الهاتف مطلوب.',

            'governorate.required' => 'المحافظة مطلوبة.',

            'address.required' => 'العنوان مطلوب.',

            'items.required' => 'يجب اختيار منتج واحد على الأقل.',

            'items.array' => 'بيانات المنتجات غير صحيحة.',

            'items.min' => 'يجب اختيار منتج واحد على الأقل.',

            'items.*.product_id.required' => 'معرف المنتج مطلوب.',

            'items.*.product_id.exists' => 'أحد المنتجات غير موجود.',

            'items.*.quantity.required' => 'الكمية مطلوبة.',

            'items.*.quantity.integer' => 'الكمية يجب أن تكون رقمًا صحيحًا.',

            'items.*.quantity.min' => 'الكمية يجب أن تكون أكبر من صفر.',
        ];
    }
}
