<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('model_photos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('catalog_model_id')->constrained()->cascadeOnDelete();
            $table->string('path');
            $table->string('alt', 150)->nullable();
            $table->unsignedTinyInteger('position')->default(0);
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('model_photos');
    }
};
