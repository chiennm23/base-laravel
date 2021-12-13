<?php

namespace App\Exports\units;

use App\Models\UnitModel;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithMultipleSheets;

class UnitExport implements WithMultipleSheets
{
    use Exportable;

    /**
     * @return array
     */
    public function sheets(): array
    {
        $sheets = [];
        for ($month = 1; $month <= 12; $month++) {
            $data = UnitModel::whereYear('created', $this->year)
                ->whereMonth('created', $month)->get();
            if (!$data->isEmpty()) {
                $sheets[] = new MultipleUnit($data, $this->year, $month);
            }
        }

        return $sheets;
    }

    /**
     * @param int $year
     * @return $this
     */
    public function forTime(int $year)
    {
        $this->year = $year;
        return $this;
    }
}
