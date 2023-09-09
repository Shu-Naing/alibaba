<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchaseAddSampleExport implements WithHeadings , ShouldAutoSize
{
    public function headings(): array
    {
        return [
            'GRN No', 
            'Received Date', 
            'Country', 
            'Item Code', 
            'Ticket', 
            'Point', 
            'Kyat', 
            'Purchased Price', 
            'Qty',
        ];
    }
}
