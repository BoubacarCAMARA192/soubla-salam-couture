<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('measurements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('clothing_type_id')->nullable()->constrained()->nullOnDelete();
            $table->string('clothing_type');
            $table->decimal('chest', 6, 1)->nullable();
            $table->decimal('waist', 6, 1)->nullable();
            $table->decimal('hips', 6, 1)->nullable();
            $table->decimal('shoulders', 6, 1)->nullable();
            $table->decimal('sleeve_length', 6, 1)->nullable();
            $table->decimal('top_length', 6, 1)->nullable();
            $table->decimal('pants_length', 6, 1)->nullable();
            $table->decimal('thigh', 6, 1)->nullable();
            $table->json('extras')->nullable();
            $table->text('notes')->nullable();
            $table->foreignId('taken_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('measurements');
    }
};
