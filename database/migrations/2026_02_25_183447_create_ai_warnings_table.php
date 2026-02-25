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
        Schema::create('ai_warnings', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_analysis_id')->constrained()->onDelete('cascade');
            $table->string('indicator_name');     // chỉ số nào bị cảnh báo
            $table->enum('level', ['info', 'warning', 'danger'])->default('info');
            $table->text('message');              // nội dung cảnh báo
            $table->boolean('is_read')->default(false);
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_warnings');
    }
};
