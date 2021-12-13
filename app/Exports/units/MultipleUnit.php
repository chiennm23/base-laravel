<?php

namespace App\Exports\units;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\FromView;
use Maatwebsite\Excel\Concerns\WithCustomStartCell;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithTitle;
use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Events\BeforeSheet;
use PhpOffice\PhpSpreadsheet\Style\Alignment;
use PhpOffice\PhpSpreadsheet\Style\Color;

class MultipleUnit implements FromView, WithTitle, WithCustomStartCell, WithEvents
{
    use Exportable;

    private $data;

    private $year;

    private $month;

    /**
     * @param $data
     * @param $year
     * @param $month
     */
    public function __construct($data, $year, $month)
    {
        $this->data = $data;
        $this->year = $year;
        $this->month = $month;
    }

    /**
     * @return string
     */
    public function title(): string
    {
        return 'Month ' . $this->month;
    }

    /**
     * @return View
     */
    public function view(): View
    {
        $title = $this->title();
        $data = $this->data;
        return view('units.unit', [
            'title' => $title,
            'units' => $data
        ]);
    }

    /**
     * @return string
     */
    public function startCell(): string
    {
        return 'B5';
    }

    /**
     * @return \Closure[]
     */
    public function registerEvents(): array
    {
        return [
            BeforeSheet::class    => function (BeforeSheet $event) {
                $event->sheet->getDelegate()->getParent()->getDefaultStyle()->applyFromArray([
                    'font' => [
                        'name'      =>  'Nimbus Mono PS',
                        'size'      =>  15,
                        'bold'      =>  true,
                        'color' => ['argb' => 'EB2B02'],
                    ]
                ]);

                $event->sheet->getDelegate()->getStyle('A3')->getFont()->getColor()->setARGB(COLOR::COLOR_RED);
                $event->sheet->getDelegate()->getStyle('A3')->applyFromArray([
                    'alignment' => [
                        'horizontal' => Alignment::HORIZONTAL_CENTER,
                        'vertical' => Alignment::VERTICAL_CENTER,
                    ]
                ]);
                $event->sheet->getDelegate()->getStyle('C3')->applyFromArray([
                    'alignment' => [
                        'wrapText' => true
                    ]
                ]);
                $event->sheet->getDelegate()->mergeCells('A3:B3');
            },

            AfterSheet::class    => function (AfterSheet $event) {
                $event->sheet->getDelegate()->getDefaultColumnDimension()->setWidth('10');
                $event->sheet->getDelegate()->getDefaultRowDimension()->setRowHeight('50');
//                $event->sheet->getDelegate()->getStyle('A3')->applyFromArray([
//                    'borders' => [
//                        'allBorders' => [
//                            'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
//                            'color' => ['argb' => '000000'],
//                        ],
//                    ]
//                ]);
            }
        ];
    }
}
