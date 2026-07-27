<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;

class UpdateProductRequest extends FormRequest
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

            'category_id' => 'sometimes|exists:categories,id',

            'title_ar' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'title_ar')->ignore($this->route('id')),
            ],

            'title_en' => [
                'sometimes',
                'string',
                'max:255',
                Rule::unique('products', 'title_en')->ignore($this->route('id')),
            ],

            'description_ar' => 'sometimes|nullable|string',

            'description_en' => 'sometimes|nullable|string',

            'images' => 'sometimes|array',

            'images.*' => 'image|mimes:jpg,jpeg,png,webp|max:2048',

            'delete_images' => 'sometimes|array',

            'delete_images.*' => 'string',

            'price_before' => 'sometimes|nullable|numeric|min:0',

            'price_after' => 'sometimes|numeric|min:0',

            'quantity' => 'sometimes|integer|min:0',

            'status' => 'sometimes|boolean',

        ];
    }

    public function messages(): array
    {
        return [

            'category_id.exists' => 'القسم غير موجود.',

            'title_ar.string' => 'اسم المنتج بالعربية يجب أن يكون نصًا.',
            'title_ar.max' => 'اسم المنتج بالعربية لا يجب أن يزيد عن 255 حرفًا.',
            'title_ar.unique' => 'اسم المنتج بالعربية مستخدم بالفعل.',

            'title_en.string' => 'اسم المنتج بالإنجليزية يجب أن يكون نصًا.',
            'title_en.max' => 'اسم المنتج بالإنجليزية لا يجب أن يزيد عن 255 حرفًا.',
            'title_en.unique' => 'اسم المنتج بالإنجليزية مستخدم بالفعل.',

            'description_ar.string' => 'الوصف بالعربية يجب أن يكون نصًا.',

            'description_en.string' => 'الوصف بالإنجليزية يجب أن يكون نصًا.',

            'images.array' => 'الصور يجب أن تكون في صورة مصفوفة.',

            'images.*.image' => 'كل ملف يجب أن يكون صورة.',
            'images.*.mimes' => 'الصورة يجب أن تكون بصيغة jpg أو jpeg أو png أو webp.',
            'images.*.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت.',

            'delete_images.array' => 'الصور المراد حذفها يجب أن تكون في صورة مصفوفة.',
            'delete_images.*.string' => 'اسم الصورة غير صحيح.',

            'price_before.numeric' => 'السعر قبل الخصم يجب أن يكون رقمًا.',
            'price_before.min' => 'السعر قبل الخصم لا يمكن أن يكون أقل من صفر.',

            'price_after.numeric' => 'السعر بعد الخصم يجب أن يكون رقمًا.',
            'price_after.min' => 'السعر بعد الخصم لا يمكن أن يكون أقل من صفر.',

            'quantity.integer' => 'الكمية يجب أن تكون رقمًا صحيحًا.',
            'quantity.min' => 'الكمية لا يمكن أن تكون أقل من صفر.',

            'status.boolean' => 'قيمة الحالة غير صحيحة.',
        ];
    }
}
