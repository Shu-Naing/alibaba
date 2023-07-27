<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OutletLevelOverviewExport implements FromCollection,WithHeadings, WithMapping, ShouldAutoSize
{
    function __construct($outletleveloverview){
        $this->outletleveloverview = $outletleveloverview;
    }
    public function headings(): array
    {
        $headings = [
            'Outlet',
            'Date',
            'Item Code',
            'Opening Qty',
            'Received Qty',
            'Issued Qty',
            'Balance',
            'Check',
        ];

        return $headings;
    }


    public function collection()
    {
        return $this->outletleveloverview;
    }

    public function map($outlevel): array
    {
        $data = [
             $outlevel->name,
             $outlevel->date,
             $outlevel->item_code,
             $outlevel->opening_qty,
             $outlevel->received_qty,
             $outlevel->issued_qty,
             $outlevel->opening_qty + $outlevel->received_qty - $outlevel->issued_qty,
            $outlevel->is_check == 1 ? 'Yes' : 'No',
        ];

        return $data;
    }
}
