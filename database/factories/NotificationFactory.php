<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Notification>
 */
class NotificationFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id' => User::factory(),
            'title'   => fake()->randomElement([
                '⚠️ Cảnh báo chỉ số bất thường',
                '✅ Kết quả phân tích hoàn tất',
                '💊 Nhắc nhở tái khám',
                '📊 Báo cáo sức khỏe tháng này',
            ]),
            'body'    => fake()->sentence(),
            'type'    => fake()->randomElement(['warning', 'advice', 'reminder', 'system']),
            'data'    => null,
            'is_read' => fake()->boolean(),
            'read_at' => fake()->boolean() ? now() : null,
        ];
    }
}
