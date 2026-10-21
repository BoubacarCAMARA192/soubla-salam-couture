<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('reviews', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('client_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('catalog_model_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->string('author_name', 100);
            $table->unsignedTinyInteger('rating');
            $table->text('comment')->nullable();
            $table->string('status', 15)->default('pending'); // pending | published | rejected
            $table->timestamps();

            $table->index('status');
        });

        Schema::create('expenses', function (Blueprint $table) {
            $table->id();
            $table->string('label');
            $table->string('category', 50)->nullable();
            $table->decimal('amount', 12, 2);
            $table->date('spent_at');
            $table->text('notes')->nullable();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('expenses');
        Schema::dropIfExists('reviews');
    }
};
