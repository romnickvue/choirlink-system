<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reminders', function (Blueprint $table) {
            $table->id();

            $table->string('title');
            $table->longText('content');

            $table->enum('priority', [
                'Normal',
                'Important',
                'Urgent',
            ])->default('Normal');

            $table->timestamp('published_at')->nullable();
            $table->date('expiration_date')->nullable();

            $table->enum('status', [
                'Draft',
                'Published',
                'Archived',
            ])->default('Draft');

            $table->foreignId('created_by')
                ->nullable()
                ->constrained('users')
                ->nullOnDelete();

            $table->timestamps();

            $table->index(['status', 'published_at']);
            $table->index('priority');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('reminders');
    }
};
