<?php

namespace App\Http\Requests\HealthProfile;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class UpdateHealthProfileRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'date_of_birth'    => 'nullable|date|before:today',
            'gender'           => 'nullable|in:male,female,other',
            'height'           => 'nullable|numeric|min:50|max:250',
            'weight'           => 'nullable|numeric|min:10|max:300',
            'blood_type'       => 'nullable|in:A,B,AB,O',
            'chronic_diseases' => 'nullable|string|max:500',
            'allergies'        => 'nullable|string|max:500',
        ];
    }

    public function messages()
    {
        return [
            'date_of_birth.before' => 'Ngày sinh không hợp lệ',
            'gender.in'            => 'Giới tính không hợp lệ',
            'height.numeric'       => 'Chiều cao phải là số',
            'height.min'           => 'Chiều cao tối thiểu 50cm',
            'height.max'           => 'Chiều cao tối đa 250cm',
            'weight.numeric'       => 'Cân nặng phải là số',
            'blood_type.in'        => 'Nhóm máu không hợp lệ (A, B, AB, O)',
        ];
    }
}
