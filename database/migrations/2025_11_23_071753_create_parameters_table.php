<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('parameters', function (Blueprint $table) {
            $table->id();

            $table->unsignedBigInteger('criteria_id');

            $table->integer('score');     
            $table->string('operator')->default('between');      
            $table->string('min_value')->nullable();
            $table->string('max_value')->nullable();

            $table->string('description')->nullable();

            $table->timestamps();

            $table->foreign('criteria_id')
                ->references('id')
                ->on('criterias')
                ->cascadeOnDelete();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('parameters');
    }
};
