<?php

namespace App\Http\Requests\Dashboard\Prescription;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Prescription;

class UpdatePrescriptionStatusRequest extends FormRequest
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
                    Prescription::PENDING,
                    Prescription::PROCESSING,
                    Prescription::COMPLETED,
                    Prescription::CANCELLED,
                ]),
            ],
        ];
    }
}
