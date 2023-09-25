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
        Schema::create('vector_models', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokumen_id');
            $table->double('magnitude');
            $table->timestamps();

            $table->foreign('dokumen_id')->references('id')->on('dokumens')->onDelete('cascade');
            
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('vector_models');
    }
};
