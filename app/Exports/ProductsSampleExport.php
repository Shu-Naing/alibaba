<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class ProductsSampleExport implements WithHeadings , ShouldAutoSize{
    // function __construct($data){
    //     $this->products = $data;
    // }
    public function headings(): array
    {
        return [
            'Item Code',
            'Image',
            'Product Name',
            'Points',
            'Tickets',
            'Kyat',
            'Received Qty',
            'Company Name',
            'Country',
            'Category',
            'Brand',
            'Unit',
            'Received Date',
            'Expired Date'
        ];
    }
   
}
