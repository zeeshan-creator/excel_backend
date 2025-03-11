<?php

namespace App\Http\Controllers;

use App\Models\ExcelData;
use App\Http\Requests\StoreExcelDataRequest;
use App\Http\Requests\UpdateExcelDataRequest;
use App\Traits\ExcelToArrayTrait;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

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
        // Validate the file input
        $validator = Validator::make($request->all(), [
            'file' => 'required|file|mimes:xlsx,xls,csv|max:2048', // Accept only Excel and CSV files, max size 2MB
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => 'Validation failed',
                'errors' => $validator->errors(),
            ], 422);
        }

        // Process the file
        $data = $this->convertExcelToArray($request->file('file'));

        // Prepare bulk insert array
        $insertData = [];

        foreach ($data as $row) {
            $formattedRow = [
                'pta_date' => $this->validateAndConvertDate($row['pta_date'] ?? null),
                'pta_value' => $this->validateAndConvertNumber($row['pta_value'] ?? null),
                'px_date' => $this->validateAndConvertDate($row['px_date'] ?? null),
                'px_value' => $this->validateAndConvertNumber($row['px_value'] ?? null),
                'pta_margin_date' => $this->validateAndConvertDate($row['pta_margin_date'] ?? null),
                'pta_margin_value' => $this->validateAndConvertNumber($row['pta_margin_value'] ?? null),
                'created_at' => now(),  // Add timestamps
                'updated_at' => now(),
            ];

            // Skip empty records
            if (!array_filter($formattedRow)) {
                continue;
            }

            $insertData[] = $formattedRow;
        }

        // Bulk insert the data
        if (!empty($insertData)) {
            ExcelData::insert($insertData);
        }

        return response()->json([
            'status' => 'success',
            'message' => 'Data imported successfully!',
            'affected_rows' => count($insertData),
        ]);
    }

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
