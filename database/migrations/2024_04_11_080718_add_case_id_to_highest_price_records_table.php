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
        Schema::table('highest_price_records', function (Blueprint $table) {
            $table->unsignedBigInteger('case_id')->after('id');

            $table->foreign('case_id')->references('id')->on('building_cases')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('highest_price_records', function (Blueprint $table) {
            $table->dropColumn('case_id');
        });
    }
};
