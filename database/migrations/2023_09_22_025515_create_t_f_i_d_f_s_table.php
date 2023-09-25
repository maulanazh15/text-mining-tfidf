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
        Schema::create('t_f_i_d_f_s', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('dokumen_id');
            $table->string('term');
            $table->double('tfidf');

            // Add foreign key constraint
            $table->foreign('dokumen_id')->references('id')->on('dokumens')->onDelete('cascade');

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('t_f_i_d_f_s');
    }
};
