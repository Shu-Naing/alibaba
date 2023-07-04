<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\FromCollection;



class OutletstockhistoryExport implements FromCollection, WithHeadings,  WithMapping, ShouldAutoSize{

   function __construct($histories){
        $this->outlethistories = $histories;
        $this->no = 0;
        $this->types = array( 
            RECIEVE_TYPE => 'Recieved',
            ISSUE_TYPE => 'Issued'
        );
        $this->branch = array(
            IS_CUSTOMER => 'Customer',
            IS_STORE => 'Store'
        );

       
    }

    public function headings(): array
    {
        return [
            'No',
            'Machine',
            'Date',
            'Item Code',
            'Quantity',
            'Recieved/Issued',
            'Branch',
            'Remark',
            'Check',
        ];
    }

    public function collection()
    {
        return $this->outlethistories;
        
    }

    public function map($outlethistories): array
    {
        $data = [
            ++$this->no,
            $outlethistories->machine_name,
            $outlethistories->date,
            $outlethistories->item_code,
            $outlethistories->quantity,
            isset($this->types[$outlethistories->type]) ? $this->types[$outlethistories->type] : '',
            isset($this->branch[$outlethistories->branch]) ? $this->branch[$outlethistories->branch] : '',
            $outlethistories->remark,
            ($outlethistories->is_check == 1) ? 'Yes' : 'No',
        ];

        return $data;
    }

   
}
