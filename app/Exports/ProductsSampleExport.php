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
            'Image Name',
            'Barcode',
            'Product Code',
            'Description',
            'Point',
            'Ticket',
            'Kyat',
            'Purchased Price',
            'Category Name',
            'Brand Name',
            'Unit Name',
            'Size Variant',
            'Alert Qty',
            'Received Qty',
            'Company Name',
            'Country',
            'Received Date',
            'Expired Date',
        ];
    }
   
}
