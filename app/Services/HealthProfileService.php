<?php

namespace App\Services;
use App\Models\HealthProfile;
use App\Models\User;

class HealthProfileService
{
    public function getProfile(User $user): HealthProfile
    {
        // Nếu chưa có thì tạo mới
        return $user->healthProfile()->firstOrCreate([
            'date_of_birth' => null,
            'gender'        => null,
        ]);
    }

    public function updateProfile(User $user, array $data): HealthProfile
    {
        $profile = $user->healthProfile()->firstOrCreate([]);

        $profile->update($data);

        return $profile->fresh();
    }

    public function getBmi(User $user): array|null
    {
        $profile = $user->healthProfile;

        if (!$profile || !$profile->height || !$profile->weight) {
            return null;
        }

        $bmi    = $profile->bmi;
        $status = match(true) {
            $bmi < 18.5 => 'Thiếu cân',
            $bmi < 23.0 => 'Bình thường',
            $bmi < 25.0 => 'Thừa cân',
            $bmi < 30.0 => 'Béo phì độ 1',
            default     => 'Béo phì độ 2',
        };

        return [
            'bmi'    => $bmi,
            'status' => $status,
        ];
    }
}
