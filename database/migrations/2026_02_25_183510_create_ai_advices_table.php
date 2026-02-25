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
        Schema::create('ai_advices', function (Blueprint $table) {
            $table->id();
            $table->foreignId('ai_analysis_id')->constrained()->onDelete('cascade');
            $table->enum('category', ['diet', 'exercise', 'medication', 'lifestyle', 'checkup']);
            // diet: ăn uống, exercise: tập luyện, medication: thuốc
            // lifestyle: lối sống, checkup: tái khám
            $table->text('advice');               // nội dung lời khuyên
            $table->integer('priority')->default(1); // độ ưu tiên 1-5
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ai_advices');
    }
};
