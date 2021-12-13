<?php

namespace App\Exports\units;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithMapping;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class UnitExportMap implements FromCollection, WithMapping
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
    }

    public function map($row): array
    {
        return [
            Date::dateTimeToExcel($row->active_start_date),
            Date::dateTimeToExcel($row->active_end_date),
            $row->display_order,
        ];
    }
}
