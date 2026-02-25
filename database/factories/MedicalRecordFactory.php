<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\MedicalRecord>
 */
class MedicalRecordFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition()
    {
        return [
            'user_id'          => User::factory(),
            'examination_date' => fake()->dateTimeBetween('-1 year', 'now'),
            'hospital_name'    => fake()->randomElement([
                'Bệnh viện Chợ Rẫy',
                'Bệnh viện Bạch Mai',
                'Bệnh viện 108',
                'Bệnh viện Đại học Y Dược',
            ]),
            'doctor_name'      => 'BS. ' . fake()->name(),
            'scan_image_path'  => null,
            'status'           => fake()->randomElement(['pending', 'processing', 'completed', 'failed']),
        ];
    }

    // State helpers
    public function completed()
    {
        return $this->state(['status' => 'completed']);
    }

    public function pending()
    {
        return $this->state(['status' => 'pending']);
    }
}
