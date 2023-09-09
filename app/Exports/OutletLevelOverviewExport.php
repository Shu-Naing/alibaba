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
            'Point',
            'Ticket',
            'Kyat',
            'Purchased Price',
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
             $outlevel->points,
             $outlevel->tickets,
             $outlevel->kyat,
             $outlevel->purchased_price,
             $outlevel->opening_qty,
             $outlevel->receive_qty,
             $outlevel->issued_qty,
             $outlevel->opening_qty + $outlevel->receive_qty - $outlevel->issued_qty,
            $outlevel->is_check == 1 ? 'Yes' : 'No',
        ];

        return $data;
    }
}
