<?php

namespace App\Imports;

use App\Models\Company\Employee;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class EmployeeExcelImport implements ToModel, WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Model|Employee|null
     */
    public function model(array $row): Model|Employee|null
    {
        return new Employee([
            'full_name' => $row['full_name'],
            'position' => $row['position'],
            'current_sc' => $row['current_sc'],
            'region' => $row['region']
        ]);
    }
}
