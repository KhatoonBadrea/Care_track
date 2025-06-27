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
        Schema::create('patients', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->unique()->constrained('users')->onDelete('cascade');
            $table->foreignId('relative_id')->nullable()->constrained('relatives')->onDelete('set null');

            $table->foreignId('blood_clique_id')->nullable()->constrained('blood_cliques')->nullOnDelete();

            $table->string('gender');
            $table->date('birth_date');
            $table->text('notes')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
    }
};
