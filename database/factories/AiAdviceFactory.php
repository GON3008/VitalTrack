<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\AiAnalysis;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiAdvice>
 */
class AiAdviceFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
{
    $advices = [
        ['category' => 'diet',      'advice' => 'Hạn chế đường và tinh bột, tăng cường rau xanh'],
        ['category' => 'exercise',  'advice' => 'Tập thể dục ít nhất 30 phút mỗi ngày'],
        ['category' => 'lifestyle', 'advice' => 'Ngủ đủ 7-8 tiếng, hạn chế căng thẳng'],
        ['category' => 'checkup',   'advice' => 'Tái khám sau 1 tháng để theo dõi chỉ số'],
        ['category' => 'medication','advice' => 'Uống thuốc đúng giờ theo chỉ định bác sĩ'],
    ];

    $advice = fake()->randomElement($advices);

    return [
        'ai_analysis_id' => AiAnalysis::factory(),
        'category'       => $advice['category'],
        'advice'         => $advice['advice'],
        'priority'       => fake()->numberBetween(1, 5),
    ];
}
}
