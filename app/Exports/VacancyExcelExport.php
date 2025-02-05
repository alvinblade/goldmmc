<?php

namespace App\Exports;

use App\Models\Vacancy;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VacancyExcelExport implements FromCollection, WithHeadings
{
    /**
     * @return Collection
     */
    public function collection(): Collection
    {
        return Vacancy::query()
            ->select('id', 'sc', 'position', 'count')
            ->get();
    }

    public function headings(): array
    {
        return [
            'ID',
            'Xidmət mərkəzi',
            'Vəzifəsi',
            'Sayı',
        ];
    }
}
