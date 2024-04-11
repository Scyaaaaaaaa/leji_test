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
        Schema::create('highest_price_records', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('trading_id');
            $table->string('case_type', 64);
            $table->string('case_name', 128);
            $table->string('manager_name', 64);
            $table->string('manager_department', 128);
            $table->unsignedBigInteger('highest_price');
            $table->timestamps();

            $table->foreign('trading_id')->references('id')->on('building_case_tradings')->onDelete('cascade');

            $table->index(['case_name', 'manager_department']);

        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('highest_price_records');
    }
};
