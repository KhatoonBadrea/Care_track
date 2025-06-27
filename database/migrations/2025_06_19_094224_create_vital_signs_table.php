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
        Schema::create('vital_signs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('patient_id')->constrained('patients')->onDelete('cascade');

            $table->float('temperature')->nullable();     // درجة الحرارة
            $table->integer('heart_rate')->nullable();    // النبض
            $table->integer('blood_pressure_systolic')->nullable();  // الضغط الأعلى
            $table->integer('blood_pressure_diastolic')->nullable(); // الضغط الأسفل
            $table->integer('respiratory_rate')->nullable(); // معدل التنفس
            $table->timestamp('measured_at')->nullable(); // وقت القياس
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vital_signs');
    }
};
