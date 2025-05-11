<?php

namespace App\Http\Controllers;

use App\Models\ExcelData;
use App\Http\Requests\StoreExcelDataRequest;
use App\Http\Requests\UpdateExcelDataRequest;
use App\Imports\ExcelData2Import;
use App\Models\ExcelData2;
use App\Traits\ExcelToArrayTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Facades\Excel;

class ExcelDataController extends Controller
{
    use ExcelToArrayTrait;

    public function list(Request $request)
    {
        // Get query params
        $sortBy = $request->get('sort_by', 'created_at'); // Default sort by created_at
        $sortOrder = $request->get('sort_order', 'desc'); // Default order is descending
        $perPage = $request->get('per_page', 10); // Default 10 records per page

        // Fetch paginated data with sorting
        $data = ExcelData::orderBy($sortBy, $sortOrder)->paginate($perPage);

        return response()->json([
            'data' => $data,
            'status' => 'success',
            'message' => 'Data retrieved successfully!',
        ]);
    }

    public function import(Request $request)
    {
        Excel::queueImport(new ExcelData2Import, $request->file('file'));
        return 'Import started. Check queue for progress.';
    }

    // public function import(Request $request)
    // {
    //     // Validate the file input
    //     $validator = Validator::make($request->all(), [
    //         'file' => 'required|file|mimes:xlsx,xls,csv', // Accept only Excel and CSV files, max size 2MB
    //     ]);

    //     if ($validator->fails()) {
    //         return response()->json([
    //             'status' => 'error',
    //             'message' => 'Validation failed',
    //             'errors' => $validator->errors(),
    //         ], 422);
    //     }

    //     // Process the file
    //     $data = $this->convertExcelToArray($request->file('file'));

    //     // Prepare bulk insert array
    //     $insertData = [];

    //     foreach ($data as $row) {
    //         $formattedRow = [
    //             'gd_number' => $row['gd_number'] ?? null,
    //             'hs_code' => $row['hs_code'] ?? null,
    //             'product_description' => $row['product_description'] ?? null,
    //             'origin' => $row['origin'] ?? null,
    //             'ntn' => $row['ntn'] ?? null,
    //             'importer_name_on_gd' => $row['importer_name_on_gd'] ?? null,
    //             'supplier_name' => $row['supplier_name'] ?? null,
    //             'port_of_shipment' => $row['port_of_shipment'] ?? null,
    //             'declared_unit_value' => $row['declared_unit_value'] ?? null,
    //             'assessed_unit_value' => $row['assessed_unit_value'] ?? null,
    //             'currency' => $row['currency'] ?? null,
    //             'quantity' => $row['quantity'] ?? null,
    //             'uom' => $row['uom'] ?? null,
    //             'import_value_in_pkr' => $row['import_value_in_pkr'] ?? null,
    //             'statutory_customs_duty' => $row['statutory_customs_duty'] ?? null,
    //             'exempted_customs_duty' => $row['exempted_customs_duty'] ?? null,
    //             'paid_customs_duty' => $row['paid_customs_duty'] ?? null,
    //             'statutory_sales_tax' => $row['statutory_sales_tax'] ?? null,
    //             'exempted_sales_tax' => $row['exempted_sales_tax'] ?? null,
    //             'paid_sales_tax' => $row['paid_sales_tax'] ?? null,
    //             'statutory_income_tax' => $row['statutory_income_tax'] ?? null,
    //             'exempted_income_tax' => $row['exempted_income_tax'] ?? null,
    //             'paid_income_tax' => $row['paid_income_tax'] ?? null,
    //             'additional_sales_tax' => $row['additional_sales_tax'] ?? null,
    //             'regulatory_duty' => $row['regulatory_duty'] ?? null,
    //             'additional_customs_duty' => $row['additional_customs_duty'] ?? null,
    //             'federal_excise_duty' => $row['federal_excise_duty'] ?? null,
    //             'antidumping_duty' => $row['antidumping_duty'] ?? null,
    //             'special_fed' => $row['special_fed'] ?? null,
    //             'other_taxes' => $row['other_taxes'] ?? null,
    //             'created_at' => now(),  // Add timestamps
    //             'updated_at' => now(),
    //         ];

    //         // // Skip empty records
    //         // if (!array_filter($formattedRow)) {
    //         //     continue;
    //         // }

    //         // $insertData[] = $formattedRow;
    //         ExcelData2::insert($formattedRow);
    //     }

    //     // Bulk insert the data
    //     if (!empty($insertData)) {
    //         // ExcelData2::insert($insertData);
    //     }

    //     return response()->json([
    //         'status' => 'success',
    //         'message' => 'Data imported successfully!',
    //         'affected_rows' => 00,
    //     ]);
    // }

    /**
     * Convert Excel date format (numeric) to YYYY-MM-DD.
     */
    private function validateAndConvertDate($date)
    {
        if (empty($date) || !is_numeric($date)) {
            return null; // Return null if the date is invalid
        }

        return \Carbon\Carbon::createFromFormat('Y-m-d', \PhpOffice\PhpSpreadsheet\Shared\Date::excelToDateTimeObject($date)->format('Y-m-d'));
    }

    /**
     * Validate and sanitize numeric values.
     */
    private function validateAndConvertNumber($value)
    {
        return is_numeric($value) ? floatval($value) : null;
    }
}
