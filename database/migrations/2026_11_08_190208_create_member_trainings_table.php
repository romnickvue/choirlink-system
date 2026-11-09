<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('member_trainings', function (Blueprint $table) {
            $table->id();

            $table->foreignId('member_id')
                ->constrained('members')
                ->restrictOnDelete();

            $table->enum('category', [
                'Mang-aawit',
                'Organista',
            ]);

            $table->date('training_start_date');

            $table->string('trainer')->nullable();

            $table->enum('status', [
                'Active',
                'Inactive',
            ])->default('Active');

            $table->enum('progress_stage', [
                'Newly registered',
                'Ongoing',
                'For evaluation',
                'Completed',
                'Discontinued',
            ])->default('Newly registered');

            $table->text('remarks')->nullable();

            $table->date('completion_date')->nullable();

            $table->timestamps();

            $table->index(['status', 'progress_stage']);
            $table->index('category');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('member_trainings');
    }
};