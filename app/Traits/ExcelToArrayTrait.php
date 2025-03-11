<?php

namespace App\Traits;

use Maatwebsite\Excel\Facades\Excel;

trait ExcelToArrayTrait
{
    /**
     * Convert Excel file to an array.
     *
     * @param \Illuminate\Http\UploadedFile $file
     * @param int $sheetIndex
     * @return array
     */
    public function convertExcelToArray($file, $sheetIndex = 0)
    {
        // Convert the Excel file to a collection
        $data = Excel::toCollection(function ($reader) use ($sheetIndex) {
            $reader->ignoreEmpty();
            $reader->setActiveSheetIndex($sheetIndex);
        }, $file)->toArray();

        $headers = $data[0][0];
        $formattedData = [];
        $_record = [];

        foreach ($data[0] as $key => $value) {
            if ($value[0] == $headers[0]) {
                continue;
            }

            foreach ($value as $inner_key => $inner_value) {
                if (isset($headers[$inner_key]) && $headers[$inner_key] !== '') {
                    $_record[$headers[$inner_key]] = $inner_value;
                }
            }

            if (!array_filter($_record)) {
                continue;
            }

            $formattedData[] = $_record;
        }

        return $formattedData;
    }
}
