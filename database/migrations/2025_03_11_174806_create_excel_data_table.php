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
        Schema::create('excel_data', function (Blueprint $table) {
            $table->id();
            $table->date('pta_date')->nullable();
            $table->integer('pta_value')->nullable();
            $table->date('px_date')->nullable();
            $table->decimal('px_value')->nullable();
            $table->date('pta_margin_date')->nullable();
            $table->decimal('pta_margin_value')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_data');
    }
};
