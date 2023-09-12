<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class SellExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    function __construct($posSellLists){
        $this->posSellLists = $posSellLists;
        $this->no = 0;
        $this->outlets = getOutlets();
        $this->payment_types = config('constants.payment_types');
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Outlet',
            'Invoice No',
            'Total Qty',
            'Total',
            'Payment Type',
            'User',
            'Invoice Date'            
        ];
      
        return $headings;
    }
    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        return $this->posSellLists;
    }

    public function map($posSellList): array
    {
        $data = [
            ++$this->no,
            $this->outlets[$posSellList->outlet_id],
            $posSellList->invoice_no,
            $posSellList->quantity,
            $posSellList->total,
            $posSellList->payment_type,
            $posSellList->name,
            $posSellList->created_at
        ];

        return $data;
    }
}
