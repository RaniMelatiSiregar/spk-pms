<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('suppliers', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->string('code');
            $table->string('name');
            $table->string('location')->nullable();
            $table->integer('price_per_kg');
            $table->integer('volume_per_month');
            $table->integer('on_time_percent');
            $table->integer('freq_per_month');
            $table->timestamps();

            $table->foreign('periode_id')
                  ->references('id')
                  ->on('periodes')
                  ->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('suppliers');
    }
};
