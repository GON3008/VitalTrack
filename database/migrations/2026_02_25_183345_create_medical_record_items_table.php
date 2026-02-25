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
        Schema::create('medical_record_items', function (Blueprint $table) {
            $table->id();
            $table->foreignId('medical_record_id')->constrained()->onDelete('cascade');
            $table->string('indicator_name');     // tên chỉ số: "Glucose", "Cholesterol"
            $table->string('indicator_code')->nullable(); // mã chỉ số: "GLU", "CHOL"
            $table->float('value');               // giá trị: 5.6
            $table->string('unit')->nullable();   // đơn vị: mmol/L, g/dL
            $table->float('normal_min')->nullable(); // ngưỡng bình thường min
            $table->float('normal_max')->nullable(); // ngưỡng bình thường max
            $table->enum('status', ['normal', 'low', 'high', 'critical'])->default('normal');
            // normal: bình thường, low: thấp, high: cao, critical: nguy hiểm
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('medical_record_items');
    }
};
