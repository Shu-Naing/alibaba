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
        $actions = [
            DESTORYDONATION => 'destroy donation',
            DISPOSAL => 'disposal',
            REUSE => 'reuse'
        ];

        $distinations = [
            TOYSUPER => 'toy super',
            STORE => 'store',
            COUNTER => 'counter'
        ];

        $this->damages = $damages;
        $this->no = 0;
        $this->actions = $actions;
        $this->distinations = $distinations;
    }

    public function headings(): array
    {
        $headings = [
            'No',
            'Month',
            'Date',
            'Voucher No',
            'Location',
            'Product Code',
            'Description',
            'Quantity',
            'Ticket',
            'Original Cost',
            'Amount (ks)',
            'Reason',
            'Name',
            'Compensation Amount',
            'Action',
            'Error',
            'Distination',
            'Damage No',
            'column1',
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
            $damages->voucher_no,
            $damages->outlet_id,
            $damages->item_code,
            $damages->description,
            $damages->quantity,
            $damages->ticket,
            $damages->original_cost,
            $damages->amount_ks,
            $damages->reason,
            $damages->name,
            $damages->amount,
            $this->actions[$damages->action],
            $damages->error,
            $this->distinations[$damages->distination],
            $damages->damage_no,
            $damages->column1,
        ];

        return $data;
    }
}
