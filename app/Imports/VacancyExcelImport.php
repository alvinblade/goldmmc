<?php

namespace App\Imports;

use App\Models\Vacancy;
use Illuminate\Database\Eloquent\Model;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class VacancyExcelImport implements ToModel,WithHeadingRow
{
    /**
     * @param array $row
     *
     * @return Model|Vacancy|null
     */
    public function model(array $row): Model|Vacancy|null
    {
        return new Vacancy([
            'sc' => $row['sc'],
            'position' => $row['position'],
            'count' => $row['number'],
        ]);
    }
}
