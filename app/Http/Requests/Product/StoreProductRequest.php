<?php

namespace App\Http\Requests\Product;

use Illuminate\Foundation\Http\FormRequest;

class StoreProductRequest extends FormRequest
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

            'category_id' => 'required|exists:categories,id',

            'title_ar' => 'required|string|max:255|unique:products,title_ar',

            'title_en' => 'required|string|max:255|unique:products,title_en',

            'description_ar' => 'nullable|string',

            'description_en' => 'nullable|string',

            'images' => 'nullable|array',

            'images.*' => 'image|max:2048',

            'price_before' => 'nullable|numeric|min:0',

            'price_after' => 'required|numeric|min:0',

            'quantity' => 'required|integer|min:0',

            'status' => 'nullable|boolean',

        ];
    }

    public function messages(): array
    {
        return [

            'category_id.required' => 'القسم مطلوب.',
            'category_id.exists' => 'القسم غير موجود.',

            'title_ar.required' => 'اسم المنتج بالعربية مطلوب.',
            'title_ar.string' => 'اسم المنتج بالعربية يجب أن يكون نصًا.',
            'title_ar.max' => 'اسم المنتج بالعربية لا يجب أن يزيد عن 255 حرفًا.',
            'title_ar.unique' => 'اسم المنتج بالعربية مستخدم بالفعل.',

            'title_en.required' => 'اسم المنتج بالإنجليزية مطلوب.',
            'title_en.string' => 'اسم المنتج بالإنجليزية يجب أن يكون نصًا.',
            'title_en.max' => 'اسم المنتج بالإنجليزية لا يجب أن يزيد عن 255 حرفًا.',
            'title_en.unique' => 'اسم المنتج بالإنجليزية مستخدم بالفعل.',

            'description_ar.string' => 'الوصف بالعربية يجب أن يكون نصًا.',

            'description_en.string' => 'الوصف بالإنجليزية يجب أن يكون نصًا.',

            'images.array' => 'الصور يجب أن تكون في صورة مصفوفة.',

            'images.*.image' => 'كل ملف يجب أن يكون صورة.',
            'images.*.max' => 'حجم الصورة لا يجب أن يتجاوز 2 ميجابايت.',

            'price_before.numeric' => 'السعر قبل الخصم يجب أن يكون رقمًا.',
            'price_before.min' => 'السعر قبل الخصم لا يمكن أن يكون أقل من صفر.',

            'price_after.required' => 'السعر بعد الخصم مطلوب.',
            'price_after.numeric' => 'السعر بعد الخصم يجب أن يكون رقمًا.',
            'price_after.min' => 'السعر بعد الخصم لا يمكن أن يكون أقل من صفر.',

            'quantity.required' => 'الكمية مطلوبة.',
            'quantity.integer' => 'الكمية يجب أن تكون رقمًا صحيحًا.',
            'quantity.min' => 'الكمية لا يمكن أن تكون أقل من صفر.',

            'status.boolean' => 'قيمة الحالة غير صحيحة.',
        ];
    }
}
