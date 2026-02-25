<?php

namespace App\Http\Requests\Notification;

use App\Http\Requests\BaseRequest;
use Illuminate\Foundation\Http\FormRequest;

class MarkAsReadRequest extends BaseRequest
{
    public function authorize()
    {
        return true;
    }

    public function rules()
    {
        return [
            // Cho phép mark nhiều notification cùng lúc
            'ids'   => 'nullable|array',
            'ids.*' => 'integer|exists:notifications,id',
        ];
    }

    public function messages()
    {
        return [
            'ids.array'    => 'Dữ liệu không hợp lệ',
            'ids.*.exists' => 'Thông báo không tồn tại',
        ];
    }
}
