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
        Schema::create('building_cases', function (Blueprint $table) {
            $table->id();
            $table->string('type',64)->nullable();
            $table->unsignedBigInteger('manager_id');
            $table->string('name', 128);
            $table->timestamps();

            $table->index('type');

            $table->foreign('manager_id')
            ->references('id')
            ->on('managers')
            ->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('building_cases');
    }
};
