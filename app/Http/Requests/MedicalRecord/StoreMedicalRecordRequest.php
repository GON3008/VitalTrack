<?php

namespace App\Http\Requests\MedicalRecord;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class StoreMedicalRecordRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            'examination_date' => 'required|date|before_or_equal:today',
            'hospital_name'    => 'nullable|string|max:255',
            'doctor_name'      => 'nullable|string|max:255',
            'scan_image'       => 'nullable|image|mimes:jpg,jpeg,png,pdf|max:5120', // 5MB
            // Validate từng chỉ số nếu nhập tay
            'items'                      => 'nullable|array',
            'items.*.indicator_name'     => 'required_with:items|string|max:100',
            'items.*.indicator_code'     => 'nullable|string|max:20',
            'items.*.value'              => 'required_with:items|numeric',
            'items.*.unit'               => 'nullable|string|max:20',
            'items.*.normal_min'         => 'nullable|numeric',
            'items.*.normal_max'         => 'nullable|numeric',
        ];
    }

    public function messages()
    {
        return [
            'examination_date.required'          => 'Vui lòng nhập ngày khám',
            'examination_date.before_or_equal'   => 'Ngày khám không được là ngày tương lai',
            'scan_image.image'                   => 'File phải là hình ảnh',
            'scan_image.max'                     => 'File tối đa 5MB',
            'items.*.indicator_name.required_with' => 'Vui lòng nhập tên chỉ số',
            'items.*.value.required_with'          => 'Vui lòng nhập giá trị chỉ số',
            'items.*.value.numeric'                => 'Giá trị chỉ số phải là số',
        ];
    }
}
