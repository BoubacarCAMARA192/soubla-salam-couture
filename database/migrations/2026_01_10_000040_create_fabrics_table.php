<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('fabrics', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('color', 50)->nullable();
            $table->decimal('quantity', 10, 2)->default(0);
            $table->string('unit', 10)->default('m');
            $table->decimal('alert_threshold', 10, 2)->default(5);
            $table->string('photo')->nullable();
            $table->text('notes')->nullable();
            $table->timestamps();
        });

        Schema::create('fabric_movements', function (Blueprint $table) {
            $table->id();
            $table->foreignId('fabric_id')->constrained()->cascadeOnDelete();
            $table->string('type', 10); // in | out
            $table->decimal('quantity', 10, 2);
            $table->foreignId('order_id')->nullable()->constrained()->nullOnDelete();
            $table->foreignId('user_id')->nullable()->constrained()->nullOnDelete();
            $table->string('reason', 255)->nullable();
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('fabric_movements');
        Schema::dropIfExists('fabrics');
    }
};
