<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MedicalRecord;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecordItem>
 */
class MedicalRecordItemFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        // Danh sách chỉ số thực tế
        $indicators = [
            ['name' => 'Glucose',      'code' => 'GLU',  'unit' => 'mmol/L', 'min' => 3.9,  'max' => 6.1],
            ['name' => 'Cholesterol',  'code' => 'CHOL', 'unit' => 'mmol/L', 'min' => 0,    'max' => 5.2],
            ['name' => 'Hồng cầu',    'code' => 'RBC',  'unit' => 'T/L',    'min' => 4.0,  'max' => 5.5],
            ['name' => 'Bạch cầu',    'code' => 'WBC',  'unit' => 'G/L',    'min' => 4.0,  'max' => 10.0],
            ['name' => 'Huyết áp tâm thu', 'code' => 'SBP', 'unit' => 'mmHg', 'min' => 90, 'max' => 120],
            ['name' => 'Triglyceride', 'code' => 'TG',   'unit' => 'mmol/L', 'min' => 0,    'max' => 1.7],
        ];

        $indicator = fake()->randomElement($indicators);
        $value     = fake()->randomFloat(2, $indicator['min'] * 0.7, $indicator['max'] * 1.3);

        // Tự động xác định status
        $status = 'normal';
        if ($value < $indicator['min']) $status = 'low';
        if ($value > $indicator['max']) $status = 'high';
        if ($value > $indicator['max'] * 1.2) $status = 'critical';

        return [
            'medical_record_id' => MedicalRecord::factory(),
            'indicator_name'    => $indicator['name'],
            'indicator_code'    => $indicator['code'],
            'value'             => $value,
            'unit'              => $indicator['unit'],
            'normal_min'        => $indicator['min'],
            'normal_max'        => $indicator['max'],
            'status'            => $status,
        ];
    }
}
