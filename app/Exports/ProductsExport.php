<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;


class ProductsExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize, WithEvents {
    function __construct($data){
        $this->products = $data;
    }
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

    public function map($product): array
    {
        return [
             $product->item_code,
             '',
             $product->product->product_name,
             $product->points,
             $product->tickets,
             $product->kyat,
             $product->outlet_item->quantity,
             $product->product->company_name,
             $product->product->country,
             $product->product->category->category_name,
             $product->product->brand->brand_name,
             $product->product->unit->name,
             $product->product->received_date,
             $product->product->expired_date,
        ];
    }
    

    public function collection()
    {
        return $this->products;
        
    }

    public function setImage($workSheet) {
        $this->collection()->each(function($product,$index) use($workSheet) {
            $drawing = new Drawing();
            $drawing->setName($product->item_code);
            $drawing->setDescription($product->item_code);
            $drawing->setPath(public_path('storage/'.$product->image));
            $drawing->setHeight(60);
            $index+=2;
            $drawing->setCoordinates("B$index");
            $drawing->setWorksheet($workSheet);
            // Get the cell range
            $cellRange = "B$index";

            // Set the image inside the cell and center it
            $workSheet->setSelectedCells($cellRange);
            $workSheet->getStyle($cellRange)
                ->getAlignment()
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER)
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER);
            });
    }

    public function registerEvents():array {
        return [
            AfterSheet::class => function (AfterSheet $event) {
                $event->sheet->getDefaultRowDimension()->setRowHeight(60);
                $workSheet = $event->sheet->getDelegate();

                $workSheet->getStyle($workSheet->calculateWorksheetDimension())
                ->getAlignment()
                ->setVertical(\PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER)
                ->setHorizontal(\PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER);
                $this->setImage($workSheet);
            },
        ];
    }
   
}
