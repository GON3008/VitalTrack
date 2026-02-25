<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('medical_records', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('examination_date'); // ngày khám
            $table->string('hospital_name')->nullable(); // tên bệnh viện
            $table->string('doctor_name')->nullable();
            $table->string('scan_image_path')->nullable(); // đường dẫn ảnh scan phiếu
            $table->enum('status', ['pending', 'processing', 'completed', 'failed'])->default('pending');
            // pending: chờ xử lý, processing: AI đang phân tích
            // completed: xong, failed: lỗi
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_records');
    }
};
