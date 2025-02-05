<?php

namespace App\Exports;

use App\Models\Company\Employee;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class EmployeeExcelExport implements FromCollection, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Employee::query()
            ->select('id', 'full_name', 'position', 'current_sc', 'region',
                'priority_1', 'priority_2', 'priority_3')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Ad/Soyad',
            'Vəzifəsi',
            'Xidmət mərkəzi',
            'Region',
            'Priority 1',
            'Priority 2',
            'Priority 3',
        ];
    }

}
