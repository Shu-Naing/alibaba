<?php

namespace App\Exports;


use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;



class ProductsSampleExport implements WithHeadings , ShouldAutoSize{
   
    public function headings(): array
    {
        return [
            'Item Code',
            'Product Name',
            'Barcode',
            'Sku',
            'Point',
            'Ticket',
            'Kyat',
            'Category Name',
            'Category Code',
            'Brand Name',
            'Unit Name',
            'Select',
            'Value',
            'Alert Qty',
            'Received Qty',
            'Purchased Price',
            'Company Name',
            'Country',
            'Received Date',
            'Expired Date',
        ];
    }
   
}
