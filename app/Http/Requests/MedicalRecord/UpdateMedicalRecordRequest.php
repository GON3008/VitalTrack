<?php

namespace App\Http\Requests\MedicalRecord;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateMedicalRecordRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'examination_date' => 'sometimes|date|before_or_equal:today',
            'hospital_name'    => 'sometimes|nullable|string|max:255',
            'doctor_name'      => 'sometimes|nullable|string|max:255',
            'scan_image'       => 'sometimes|nullable|image|mimes:jpg,jpeg,png,pdf|max:5120',
        ];
    }
}
