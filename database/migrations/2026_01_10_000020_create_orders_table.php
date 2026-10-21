<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('orders', function (Blueprint $table) {
            $table->id();
            $table->string('reference', 20)->unique();
            $table->foreignId('client_id')->constrained()->cascadeOnDelete();
            $table->foreignId('tailleur_id')->nullable()->constrained('users')->nullOnDelete();
            $table->foreignId('catalog_model_id')->nullable()->constrained()->nullOnDelete();
            $table->string('type', 10)->default('order'); // order | quote
            $table->text('description')->nullable();
            $table->string('inspiration_photo')->nullable();
            $table->string('fabric')->nullable();
            $table->date('event_date')->nullable();
            $table->date('delivery_date')->nullable();
            $table->string('status', 20)->default('requested')->index();
            $table->string('comment', 150)->nullable();
            $table->decimal('amount', 12, 2)->default(0);
            $table->text('tailleur_notes')->nullable();
            $table->timestamps();

            $table->index('delivery_date');
        });

        Schema::create('order_status_history', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('status', 20);
            $table->string('comment', 255)->nullable();
            $table->string('photo')->nullable();
            $table->foreignId('changed_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });

        Schema::create('payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('order_id')->constrained()->cascadeOnDelete();
            $table->string('receipt_number', 30)->unique();
            $table->decimal('amount', 12, 2);
            $table->string('method', 20);
            $table->string('type', 20)->default('deposit'); // deposit | balance | extra
            $table->date('paid_at');
            $table->foreignId('recorded_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('payments');
        Schema::dropIfExists('order_status_history');
        Schema::dropIfExists('orders');
    }
};
