<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelData2 extends Model
{
    use HasFactory;

    protected $table = 'excel_data_2';

    protected $fillable = [
        'date',
        'gd_number',
        'hs_code',
        'product_description',
        'origin',
        'ntn',
        'importer_name_on_gd',
        'supplier_name',
        'port_of_shipment',
        'declared_unit_value',
        'assessed_unit_value',
        'currency',
        'quantity',
        'uom',
        'import_value_in_pkr',
        'statutory_customs_duty',
        'exempted_customs_duty',
        'paid_customs_duty',
        'statutory_sales_tax',
        'exempted_sales_tax',
        'paid_sales_tax',
        'statutory_income_tax',
        'exempted_income_tax',
        'paid_income_tax',
        'additional_sales_tax',
        'regulatory_duty',
        'additional_customs_duty',
        'federal_excise_duty',
        'antidumping_duty',
        'special_fed',
        'other_taxes',
    ];
}
