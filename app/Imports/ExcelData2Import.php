<?php

// app/Imports/ExcelData2Import.php

namespace App\Imports;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithChunkReading;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ExcelData2Import implements ToCollection, WithHeadingRow, WithChunkReading, ShouldQueue
{
    public function collection(Collection $rows)
    {
        $data = [];

        foreach ($rows as $row) {
            $gdNumber = $row['gd_number'] ?? null;

            $data[] = [
                'gd_number' => $gdNumber,
                'date' => $this->extractDateFromGdNumber($gdNumber),
                'hs_code' => $row['hs_code'] ?? null,
                'product_description' => $row['product_description'] ?? null,
                'origin' => $row['origin'] ?? null,
                'ntn' => $row['ntn'] ?? null,
                'importer_name_on_gd' => $row['importer_name_on_gd'] ?? null,
                'supplier_name' => $row['supplier_name'] ?? null,
                'port_of_shipment' => $row['port_of_shipment'] ?? null,
                'declared_unit_value' => $row['declared_unit_value'] ?? null,
                'assessed_unit_value' => $row['assessed_unit_value'] ?? null,
                'currency' => $row['currency'] ?? null,
                'quantity' => $row['quantity'] ?? null,
                'uom' => $row['uom'] ?? null,
                'import_value_in_pkr' => $row['import_value_in_pkr'] ?? null,
                'statutory_customs_duty' => $row['statutory_customs_duty'] ?? null,
                'exempted_customs_duty' => $row['exempted_customs_duty'] ?? null,
                'paid_customs_duty' => $row['paid_customs_duty'] ?? null,
                'statutory_sales_tax' => $row['statutory_sales_tax'] ?? null,
                'exempted_sales_tax' => $row['exempted_sales_tax'] ?? null,
                'paid_sales_tax' => $row['paid_sales_tax'] ?? null,
                'statutory_income_tax' => $row['statutory_income_tax'] ?? null,
                'exempted_income_tax' => $row['exempted_income_tax'] ?? null,
                'paid_income_tax' => $row['paid_income_tax'] ?? null,
                'additional_sales_tax' => $row['additional_sales_tax'] ?? null,
                'regulatory_duty' => $row['regulatory_duty'] ?? null,
                'additional_customs_duty' => $row['additional_customs_duty'] ?? null,
                'federal_excise_duty' => $row['federal_excise_duty'] ?? null,
                'antidumping_duty' => $row['antidumping_duty'] ?? null,
                'special_fed' => $row['special_fed'] ?? null,
                'other_taxes' => $row['other_taxes'] ?? null,
                'created_at' => now(),
                'updated_at' => now(),
            ];
        }

        DB::table('excel_data_2')->insert($data); // one bulk insert per chunk
    }


    protected function extractDateFromGdNumber(?string $gdNumber): ?string
    {
        if ($gdNumber && preg_match('/(\d{2}-\d{2}-\d{4})$/', $gdNumber, $matches)) {
            try {
                return \Carbon\Carbon::createFromFormat('d-m-Y', $matches[1])->format('Y-m-d');
            } catch (\Exception $e) {
                return null; // In case of invalid date format
            }
        }

        return null;
    }


    public function chunkSize(): int
    {
        return 1000;
    }
}
