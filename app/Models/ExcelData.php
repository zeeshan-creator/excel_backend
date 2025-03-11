<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExcelData extends Model
{
    use HasFactory;
    protected $table = 'excel_data';

    protected $fillable = [
        'pta_date',
        'pta_value',
        'px_date',
        'px_value',
        'pta_margin_date',
        'pta_margin_value',
    ];
}
