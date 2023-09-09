<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;



class OutletstockoverviewsExport implements FromCollection, WithHeadings,  WithMapping, ShouldAutoSize{

   function __construct($outletstockoverviews){
        $this->outletstocks = $outletstockoverviews;
        $this->no = 0;
    }

    public function headings(): array
    {
        return [
            'No',
            'Machine Name',
            'Item Code',
            'Point',
            'Ticket',
            'Kyat',
            'Purchased Price',
            'Opening Qty',
            'Recieved Qty',
            'Issue Qty',
            'Balance Qty',
            'Check',
            'Physical Qty',
            'Difference Qty',
        ];
    }

    public function collection()
    {
        return $this->outletstocks;
        
    }

    public function map($outletstocks): array
    {
        $balance_qty = ($outletstocks->opening_qty + $outletstocks->receive_qty) - $outletstocks->issued_qty;
        $data = [
            ++$this->no,
            $outletstocks->name,
            $outletstocks->item_code,
            $outletstocks->points,
            $outletstocks->tickets,
            $outletstocks->kyat,
            $outletstocks->purchased_price,
            ($outletstocks->opening_qty == 0) ? '0' : $outletstocks->opening_qty,
            ($outletstocks->receive_qty == 0) ? '0' : $outletstocks->receive_qty,
            ($outletstocks->issued_qty == 0) ? '0' : $outletstocks->issued_qty,
            ($balance_qty == 0) ? '0' : $balance_qty,
            ($outletstocks->is_check == 1) ? 'Yes' : 'No',
            ($outletstocks->physical_qty == 0) ? '0' : $outletstocks->physical_qty,
            ($outletstocks->difference_qty == 0) ? '0' : $outletstocks->difference_qty,
        ];

        return $data;
    }

   
}
