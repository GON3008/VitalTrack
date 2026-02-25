<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\MedicalRecord;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AiAnalysis>
 */
class AiAnalysisFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        $score = fake()->randomFloat(1, 40, 100);
        $level = match (true) {
            $score >= 80 => 'excellent',
            $score >= 60 => 'good',
            $score >= 40 => 'fair',
            default      => 'poor',
        };

        return [
            'medical_record_id'      => MedicalRecord::factory(),
            'user_id'                => User::factory(),
            'summary'                => fake()->paragraph(3),
            'health_score'           => $score,
            'health_score_level'     => $level,
            'trend_data'             => [
                'labels' => ['T1', 'T2', 'T3', 'T4', 'T5', 'T6'],
                'glucose'      => [5.1, 5.4, 5.8, 5.6, 6.0, 5.9],
                'cholesterol'  => [4.2, 4.5, 4.8, 5.0, 5.1, 4.9],
            ],
            'compared_with_previous' => [
                'glucose'     => ['before' => 5.4, 'after' => 5.9, 'change' => '+0.5'],
                'cholesterol' => ['before' => 4.5, 'after' => 4.9, 'change' => '+0.4'],
            ],
        ];
    }
}
