<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class OutletstockoverviewSampleExport implements WithHeadings , ShouldAutoSize{
   
    public function headings(): array
    {
        return [
            'Date',
            'Outlet Name',
            'Machine Name',
            'Item Code',
            'Opening Quantity',
        ];
    }
   
}
