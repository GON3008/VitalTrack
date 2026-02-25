<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AiAnalysis;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiWarning>
 */
class AiWarningFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $warnings = [
            ['indicator' => 'Glucose',     'msg' => 'Chỉ số đường huyết cao hơn mức bình thường'],
            ['indicator' => 'Cholesterol', 'msg' => 'Cholesterol có xu hướng tăng dần'],
            ['indicator' => 'Huyết áp',   'msg' => 'Huyết áp tâm thu vượt ngưỡng an toàn'],
            ['indicator' => 'Bạch cầu',   'msg' => 'Bạch cầu thấp, cần theo dõi thêm'],
        ];

        $warning = fake()->randomElement($warnings);

        return [
            'ai_analysis_id' => AiAnalysis::factory(),
            'indicator_name' => $warning['indicator'],
            'level'          => fake()->randomElement(['info', 'warning', 'danger']),
            'message'        => $warning['msg'],
            'is_read'        => fake()->boolean(),
        ];
    }
}
