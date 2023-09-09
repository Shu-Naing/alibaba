<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class PurchaseExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($purchaseItems){

        $this->purchaseItems = $purchaseItems;
        $this->no = 0;
        $this->country = config('constants.countries');
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'GRN No',
            'Received Date',
            'Country',
            'Item Code',
            'Ticket',  
            'Point',   
            'Kyat',   
            'Purchased Price	',   
            'Qty',
        ];
      
        return $headings;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $this->purchaseItems;
    }

    public function map($purchaseItems): array
    {
        $data = [
            ++$this->no,
            $purchaseItems->grn_no,
            $purchaseItems->received_date,
            $this->country[$purchaseItems->country],
            $purchaseItems->item_code,
            $purchaseItems->tickets,
            $purchaseItems->points,
            $purchaseItems->kyat,
            $purchaseItems->purchased_price,
            $purchaseItems->quantity,
        ];

        return $data;
    }
}
