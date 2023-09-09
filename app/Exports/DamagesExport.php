<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class DamagesExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    function __construct($damages){


        $this->damages = $damages;
        $this->no = 0;
        $this->actions = config('constants.action');
        $this->distinations = config('constants.distination');
        $this->outlet_id = getOutlets();
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Month',
            'Date',
            'Damage No',
            'Location',
            'Product Code',
            'Point',
            'Ticket',
            'Kyat',
            'Purchase Price',
            'Quantity',
            'Total Amount',
            'Reason',
            'Name',
            'Compensation Amount',
            'Action',
            'Error',
            'Distination',
        ];
      
        return $headings;
    }

    /**
    * @return \Illuminate\Support\Collection
    */
    public function collection()
    {
        //
        return $this->damages;
    }

    public function map($damages): array
    {
        $data = [
            ++$this->no,
            date("M",strtotime($damages->date)),
            $damages->date,
            $damages->damage_no,
            $this->outlet_id[$damages->outlet_id],
            $damages->item_code,
            $damages->point,
            $damages->ticket,
            $damages->kyat,
            $damages->purchase_price,
            $damages->quantity,
            $damages->total,
            $damages->reason,
            $damages->name,
            $damages->amount,
            isset($this->actions[$damages->action]) ? $this->actions[$damages->action] : '',
            $damages->error,
            isset($this->distinations[$damages->distination]) ? $this->distinations[$damages->distination] : ''
        ];

        return $data;
    }
}
