<?php

namespace App\Http\Requests\Category;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
    use App\Models\Category;

class UpdateCategoryRequest extends FormRequest
{
    public function authorize(): bool
    {
        return true;
    }


public function rules(): array
{
    return [
        'name_ar' => [
            'required',
            'string',
            'max:255',
            Rule::unique(Category::class, 'name_ar')->ignore($this->route('id')),
        ],

        'name_en' => [
            'required',
            'string',
            'max:255',
            Rule::unique(Category::class, 'name_en')->ignore($this->route('id')),
        ],

        'image' => 'nullable|image|max:2048',
        'status' => 'nullable|boolean',
    ];
}

    public function messages(): array
    {
        return [
            'name_ar.required' => 'الاسم باللغة العربية مطلوب.',
            'name_ar.string'   => 'الاسم باللغة العربية يجب أن يكون نصًا.',
            'name_ar.max'      => 'الاسم باللغة العربية يجب ألا يزيد عن 255 حرفًا.',
            'name_ar.unique'   => 'الاسم باللغة العربية مستخدم بالفعل.',

            'name_en.required' => 'الاسم باللغة الإنجليزية مطلوب.',
            'name_en.string'   => 'الاسم باللغة الإنجليزية يجب أن يكون نصًا.',
            'name_en.max'      => 'الاسم باللغة الإنجليزية يجب ألا يزيد عن 255 حرفًا.',
            'name_en.unique'   => 'الاسم باللغة الإنجليزية مستخدم بالفعل.',

            'image.image'      => 'الملف المرفوع يجب أن يكون صورة.',
            'image.max'        => 'حجم الصورة يجب ألا يتجاوز 2 ميجابايت.',

            'status.boolean'   => 'قيمة الحالة غير صحيحة.',
        ];
    }
}
