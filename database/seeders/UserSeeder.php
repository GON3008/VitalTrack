<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\HealthProfile;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        // Dùng firstOrCreate tránh trùng email
        $admin = User::firstOrCreate(
            ['email' => 'admin@vitaltrack.com'],
            [
                'name'     => 'Admin VitalTrack',
                'password' => bcrypt('password'),
                'role'     => 'admin',
            ]
        );

        $admin->healthProfile()->firstOrCreate([
            'date_of_birth' => '1990-01-01',
            'gender'        => 'male',
            'height'        => 170,
            'weight'        => 65,
            'blood_type'    => 'O',
        ]);

        // 10 user random
        User::factory(10)->create()->each(function ($user) {
            $user->healthProfile()->create(
                HealthProfile::factory()->definition()
            );
        });
    }
}
