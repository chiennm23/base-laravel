<?php

namespace App\Http\Controllers;

use App\Exports\units\UnitExport;
use Maatwebsite\Excel\Excel;

class UnitController extends Controller
{
    /**
     * @return \Illuminate\Http\Response|\Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function export()
    {
        $year = 2020;
        return (new UnitExport())->forTime($year)->download('units.xlsx', Excel::XLSX);
    }
}
