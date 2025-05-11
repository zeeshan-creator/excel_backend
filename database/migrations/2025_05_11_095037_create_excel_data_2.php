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
        Schema::create('excel_data_2', function (Blueprint $table) {
            $table->id();
            $table->date('date')->nullable();
            $table->string('gd_number')->nullable();
            $table->string('hs_code')->nullable();
            $table->text('product_description')->nullable();
            $table->string('origin')->nullable();
            $table->string('ntn')->nullable();
            $table->string('importer_name_on_gd')->nullable();
            $table->string('supplier_name')->nullable();
            $table->string('port_of_shipment')->nullable();
            $table->decimal('declared_unit_value', 15, 4)->nullable();
            $table->decimal('assessed_unit_value', 15, 4)->nullable();
            $table->string('currency')->nullable();
            $table->decimal('quantity', 15, 4)->nullable();
            $table->string('uom')->nullable();
            $table->decimal('import_value_in_pkr', 20, 2)->nullable();
            $table->decimal('statutory_customs_duty', 15, 2)->nullable();
            $table->decimal('exempted_customs_duty', 15, 2)->nullable();
            $table->decimal('paid_customs_duty', 15, 2)->nullable();
            $table->decimal('statutory_sales_tax', 15, 2)->nullable();
            $table->decimal('exempted_sales_tax', 15, 2)->nullable();
            $table->decimal('paid_sales_tax', 15, 2)->nullable();
            $table->decimal('statutory_income_tax', 15, 2)->nullable();
            $table->decimal('exempted_income_tax', 15, 2)->nullable();
            $table->decimal('paid_income_tax', 15, 2)->nullable();
            $table->decimal('additional_sales_tax', 15, 2)->nullable();
            $table->decimal('regulatory_duty', 15, 2)->nullable();
            $table->decimal('additional_customs_duty', 15, 2)->nullable();
            $table->decimal('federal_excise_duty', 15, 2)->nullable();
            $table->decimal('antidumping_duty', 15, 2)->nullable();
            $table->decimal('special_fed', 15, 2)->nullable();
            $table->decimal('other_taxes', 15, 2)->nullable();
            $table->timestamps(); // includes created_at and updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('excel_data_2');
    }
};
