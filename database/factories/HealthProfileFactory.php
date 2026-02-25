<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use App\Models\User;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\HealthProfile>
 */
class HealthProfileFactory extends Factory
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
            'date_of_birth'    => fake()->dateTimeBetween('-60 years', '-18 years'),
            'gender'           => fake()->randomElement(['male', 'female']),
            'height'           => fake()->randomFloat(1, 150, 190), // cm
            'weight'           => fake()->randomFloat(1, 45, 100),  // kg
            'blood_type'       => fake()->randomElement(['A', 'B', 'AB', 'O']),
            'chronic_diseases' => fake()->randomElement([null, 'Tiểu đường', 'Huyết áp cao', 'Tim mạch']),
            'allergies'        => fake()->randomElement([null, 'Penicillin', 'Aspirin']),
        ];
    }
}
