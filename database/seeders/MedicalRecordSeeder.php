<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordItem;
use App\Models\AiAnalysis;
use App\Models\AiWarning;
use App\Models\AiAdvice;

class MedicalRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run()
    {
        $users = User::where('role', 'user')->get();

        foreach ($users as $user) {
            // Mỗi user có 3 lần khám
            MedicalRecord::factory(3)
                ->completed()
                ->create(['user_id' => $user->id])
                ->each(function ($record) use ($user) {
                    // Mỗi lần khám có 6 chỉ số
                    MedicalRecordItem::factory(6)->create([
                        'medical_record_id' => $record->id
                    ]);

                    // Tạo AI analysis cho mỗi lần khám
                    $analysis = AiAnalysis::factory()->create([
                        'medical_record_id' => $record->id,
                        'user_id'           => $user->id,
                    ]);

                    // Tạo warnings và advices
                    AiWarning::factory(2)->create(['ai_analysis_id' => $analysis->id]);
                    AiAdvice::factory(3)->create(['ai_analysis_id' => $analysis->id]);
                });
        }
    }
}
