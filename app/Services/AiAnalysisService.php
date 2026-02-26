<?php

namespace App\Services;

use App\Models\AiAnalysis;
use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Support\Facades\Http;

class AiAnalysisService
{
    // URL của Node.js AI Service
    protected string $aiServiceUrl;

    public function __construct(
        protected NotificationService $notificationService
    ) {
        $this->aiServiceUrl = config('services.ai.url', 'http://localhost:3000');
    }

    public function analyze(MedicalRecord $record, User $user): AiAnalysis
    {
        // Lấy lịch sử khám trước để so sánh
        $previousRecords = $user->medicalRecords()
            ->with('items')
            ->where('id', '!=', $record->id)
            ->completed()
            ->orderBy('examination_date', 'desc')
            ->take(5)
            ->get();

        // Gửi sang Node.js AI Service
        $response = Http::timeout(60)->post("{$this->aiServiceUrl}/analyze", [
            'record'           => $record->load('items'),
            'previous_records' => $previousRecords,
            'user_profile'     => $user->healthProfile,
        ]);

        if ($response->failed()) {
            $record->update(['status' => 'failed']);
            throw new \Exception('AI Service không phản hồi');
        }

        $aiResult = $response->json();

        // Lưu kết quả AI
        $analysis = $this->storeAnalysis($record, $user, $aiResult);

        // Cập nhật status record
        $record->update(['status' => 'completed']);

        // Gửi notification nếu có cảnh báo nguy hiểm
        $this->notifyIfDanger($user, $analysis);

        return $analysis;
    }

    public function getHistory(User $user): array
    {
        // Lấy tất cả kết quả phân tích để vẽ biểu đồ
        $analyses = AiAnalysis::where('user_id', $user->id)
            ->with('medicalRecord')
            ->orderBy('created_at', 'asc')
            ->get();

        return [
            'analyses'    => $analyses,
            'trend_chart' => $this->buildTrendChart($analyses),
        ];
    }

    private function storeAnalysis(MedicalRecord $record, User $user, array $aiResult): AiAnalysis
    {
        $analysis = AiAnalysis::create([
            'medical_record_id'      => $record->id,
            'user_id'                => $user->id,
            'summary'                => $aiResult['summary'],
            'health_score'           => $aiResult['health_score'],
            'health_score_level'     => $aiResult['health_score_level'],
            'trend_data'             => $aiResult['trend_data'] ?? null,
            'compared_with_previous' => $aiResult['compared_with_previous'] ?? null,
        ]);

        // Lưu warnings
        foreach ($aiResult['warnings'] ?? [] as $warning) {
            $analysis->warnings()->create($warning);
        }

        // Lưu advices
        foreach ($aiResult['advices'] ?? [] as $advice) {
            $analysis->advices()->create($advice);
        }

        return $analysis->fresh(['warnings', 'advices']);
    }

    private function notifyIfDanger(User $user, AiAnalysis $analysis): void
    {
        $dangerWarnings = $analysis->warnings()->danger()->get();

        if ($dangerWarnings->isNotEmpty()) {
            $this->notificationService->send($user, [
                'title' => '⚠️ Cảnh báo sức khỏe nghiêm trọng',
                'body'  => "Có {$dangerWarnings->count()} chỉ số cần chú ý ngay",
                'type'  => 'warning',
                'data'  => ['analysis_id' => $analysis->id],
            ]);
        }
    }

    private function buildTrendChart(mixed $analyses): array
    {
        // Format data cho biểu đồ frontend
        return $analyses->map(fn($a) => [
            'date'         => $a->medicalRecord->examination_date,
            'health_score' => $a->health_score,
            'trend_data'   => $a->trend_data,
        ])->toArray();
    }
}
