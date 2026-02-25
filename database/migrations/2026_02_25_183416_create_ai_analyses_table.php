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
        Schema::create('ai_analyses', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->text('summary');              // tóm tắt tổng thể
            $table->enum('health_score_level', ['excellent', 'good', 'fair', 'poor'])->nullable();
            $table->float('health_score')->nullable(); // điểm sức khỏe: 0-100
            $table->json('trend_data')->nullable();    // dữ liệu xu hướng để vẽ biểu đồ
            $table->json('compared_with_previous')->nullable(); // so sánh với lần trước
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_analyses');
    }
};
