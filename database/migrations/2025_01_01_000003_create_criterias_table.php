<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up(): void
    {
        Schema::create('criterias', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('periode_id');
            $table->string('code');         
            $table->string('name');
            $table->enum('type', ['Benefit', 'Cost']);
            $table->decimal('weight', 5, 2);
            $table->string('slug')->nullable();
            $table->timestamps();

            $table->foreign('periode_id')->references('id')->on('periodes')->onDelete('cascade');
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('criterias');
    }
};
