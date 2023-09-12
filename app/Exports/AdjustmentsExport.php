<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AdjustmentsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    /**
    * @return \Illuminate\Support\Collection
    */
    function __construct($outlets, $adjustments){

        $this->outlets = getOutlets();
        $this->adjustments = $adjustments;
        $this->no = 0;
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Adj_no',
            'Date',  
            'Location',   
            'Product Code',   
            'Qty',
            'remark',
        ];
      
        return $headings;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $this->adjustments;
    }

    public function map($adjustments): array
    {
        $data = [
            ++$this->no,
            $adjustments->adj_no,
            $adjustments->date,
            $this->outlets[$adjustments->outlet_id],
            $adjustments->item_code,
            $adjustments->adjustment_qty,
            $adjustments->remark,
        ];

        return $data;
    }
}
