<?php

namespace App\Services;

use App\Models\MedicalRecord;
use App\Models\User;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Collection;
use App\Services\AiAnalysisService;
use App\Services\NotificationService;

class MedicalRecordService
{
    public function __construct(
        protected AiAnalysisService $aiAnalysisService,
        protected NotificationService $notificationService,
    ) {}

    public function getAll(User $user): Collection
    {
        return $user->medicalRecords()
            ->with(['items', 'aiAnalysis.warnings', 'aiAnalysis.advices'])
            ->orderBy('examination_date', 'desc')
            ->get();
    }

    public function getById(User $user, int $id): MedicalRecord
    {
        return $user->medicalRecords()
            ->with(['items', 'aiAnalysis.warnings', 'aiAnalysis.advices'])
            ->findOrFail($id);
    }

    public function store(User $user, array $data): MedicalRecord
    {
        // Upload ảnh scan nếu có
        $scanImagePath = null;
        if (isset($data['scan_image'])) {
            $scanImagePath = $data['scan_image']->store(
                "medical_records/{$user->id}",
                'public'
            );
        }

        // Tạo medical record
        $record = $user->medicalRecords()->create([
            'examination_date' => $data['examination_date'],
            'hospital_name'    => $data['hospital_name'] ?? null,
            'doctor_name'      => $data['doctor_name'] ?? null,
            'scan_image_path'  => $scanImagePath,
            'status'           => 'pending',
        ]);

        // Nếu có nhập tay các chỉ số
        if (!empty($data['items'])) {
            $this->storeItems($record, $data['items']);
            // Chuyển thẳng sang AI phân tích
            $this->processAiAnalysis($record, $user);
        }

        // Nếu có ảnh scan → gửi sang AI xử lý
        if ($scanImagePath) {
            $this->processAiAnalysis($record, $user);
        }

        return $record->fresh(['items', 'aiAnalysis']);
    }

    public function update(User $user, int $id, array $data): MedicalRecord
    {
        $record = $user->medicalRecords()->findOrFail($id);

        if (isset($data['scan_image'])) {
            // Xóa ảnh cũ
            if ($record->scan_image_path) {
                Storage::disk('public')->delete($record->scan_image_path);
            }
            $data['scan_image_path'] = $data['scan_image']->store(
                "medical_records/{$user->id}",
                'public'
            );
        }

        $record->update($data);

        return $record->fresh();
    }

    public function delete(User $user, int $id): void
    {
        $record = $user->medicalRecords()->findOrFail($id);

        if ($record->scan_image_path) {
            Storage::disk('public')->delete($record->scan_image_path);
        }

        $record->delete();
    }

    // Lưu các chỉ số
    private function storeItems(MedicalRecord $record, array $items): void
    {
        foreach ($items as $item) {
            $status = 'normal';

            if (isset($item['normal_min']) && $item['value'] < $item['normal_min']) {
                $status = 'low';
            }
            if (isset($item['normal_max']) && $item['value'] > $item['normal_max']) {
                $status = 'high';
            }
            if (isset($item['normal_max']) && $item['value'] > $item['normal_max'] * 1.2) {
                $status = 'critical';
            }

            $record->items()->create([...$item, 'status' => $status]);
        }
    }

    // Gửi sang AI phân tích
    private function processAiAnalysis(MedicalRecord $record, User $user): void
    {
        $record->update(['status' => 'processing']);

        // Gọi AiAnalysisService xử lý
        $this->aiAnalysisService->analyze($record, $user);
    }
}
