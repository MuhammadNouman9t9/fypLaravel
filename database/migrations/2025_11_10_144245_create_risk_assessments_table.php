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
        Schema::create('risk_assessments', function (Blueprint $table) {
            $table->id();
            $table->uuid('uuid')->unique();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('session_id')->nullable()->index();
            $table->string('property_type')->nullable();
            $table->integer('property_size')->nullable();
            $table->string('occupancy_pattern')->nullable();
            $table->string('neighborhood_profile')->nullable();
            $table->decimal('score', 5, 2);
            $table->string('risk_level', 32);
            $table->json('recommendations')->nullable();
            $table->json('analysis')->nullable();
            $table->json('input_payload')->nullable();
            $table->json('metadata')->nullable();
            $table->timestamp('analyzed_at')->useCurrent();
            $table->timestamps();

            $table->index(['risk_level', 'analyzed_at']);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('risk_assessments');
    }
};
