<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('members', function (Blueprint $table) {
            $table->id();

            $table->string('first_name');
            $table->string('middle_name')->nullable();
            $table->string('last_name');
            $table->string('suffix')->nullable();

            $table->enum('gender', ['Male', 'Female']);

            $table->date('birthday');

            $table->string('contact_number')->nullable();

            $table->foreignId('prk_group_id')
                ->nullable()
                ->constrained('prk_groups')
                ->nullOnDelete();

            $table->enum('tungkulin', [
                'Mang-aawit',
                'Organista',
            ])->default('Mang-aawit');

            $table->enum('status', [
                'Active',
                'Inactive',
            ])->default('Active');

            $table->string('profile_photo')->nullable();

            $table->text('remarks')->nullable();

            $table->date('date_registered');

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('members');
    }
};