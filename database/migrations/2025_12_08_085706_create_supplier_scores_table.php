<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void {
        Schema::create('supplier_scores', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('supplier_id');
            $table->unsignedBigInteger('criteria_id');
            $table->unsignedBigInteger('parameter_id')->nullable();
            $table->decimal('raw_value', 12, 2)->nullable();
            $table->integer('score')->nullable(); 
            $table->timestamps();

            $table->foreign('supplier_id')->references('id')->on('suppliers')->onDelete('cascade');
            $table->foreign('criteria_id')->references('id')->on('criterias')->onDelete('cascade');
            $table->foreign('parameter_id')->references('id')->on('parameters')->onDelete('set null');

            $table->unique(['supplier_id','criteria_id']);
        });
    }

    public function down(): void {
        Schema::dropIfExists('supplier_scores');
    }
};
