<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class OutletleveloverviewSampleExport implements WithHeadings , ShouldAutoSize{
   
    public function headings(): array
    {
        return [
            'Date',
            'Outlet Name',
            'Item Code',
            'Opening Quantity',
        ];
    }
   
}
