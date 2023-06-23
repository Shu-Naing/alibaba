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
    function __construct($reports,$outlets){
        $this->products = $reports;
        $this->outlets = $outlets;
    }
    public function headings(): array
    {
        $headings = [
            'Item Code',
            'Photo',
            'Product Name',
            'Point',
            'Ticket',
            'Price (WS)',
            'Size',
            'Received Date',
            'Company Name',
            'Country',
            'Category',
            'Brand',
            'UOM',
            'Inventory Store Balance',
            'Total Store Balance',
            'Total Machine Balance',
            'Grand Total Balance',
            'Grand Total Price'
        ];
    
        $index = array_search('Inventory Store Balance', $headings);
    
        foreach ($this->outlets as $outlet) {
            $outletHeading = $outlet['name'] . ' Store Balance';
            $headings = array_merge(
                array_slice($headings, 0, $index + 1),
                [$outletHeading],
                array_map(function ($machine) use ($outlet) {
                    return $machine['name'] . ' Machine Balance';
                }, $outlet['machines']->toArray()),
                array_slice($headings, $index + 1)
            );
            $index += count($outlet['machines']) + 1;
        }
    

      
        return $headings;
    }
    

    public function map($product): array
    {

        $inventory_store_balance = outlet_stock($product->id);
        $total_store_balance =  total_store_stock($product->id);
        $total_machine_balance = total_machine_stock($product->id);
        $grand_total_balance = total_store_stock($product->id) + total_machine_stock($product->id);
        $grand_total_price = $product->kyat * (total_store_stock($product->id) + total_machine_stock($product->id));

        $data = [
            $product->item_code,
            '',
            $product->product->product_name, 
            (!isset($product->points) || $product->points == 0) ? '0' : $product->points, 
            (!isset($product->tickets) || $product->tickets == 0) ? '0' : $product->tickets,
            (!isset($product->kyat) || $product->kyat == 0) ? '0' : $product->kyat,
            $product->value, 
            $product->product->received_date, 
            $product->product->company_name, 
            $product->product->country, 
            $product->product->category->category_name, 
            $product->product->brand->brand_name, 
            $product->product->unit->name, 
            (!isset($inventory_store_balance) || $inventory_store_balance == 0) ? '0' : $inventory_store_balance,
            (!isset($total_store_balance) || $total_store_balance == 0) ? '0' : $total_store_balance,
            (!isset($total_machine_balance) || $total_machine_balance == 0) ? '0' : $total_machine_balance,
            (!isset($grand_total_balance) || $grand_total_balance == 0) ? '0' : $grand_total_balance,
            (!isset($grand_total_price) || $grand_total_price == 0) ? '0' : $grand_total_price,
           
    ];


    $index = 13;
    $product_id = $product->id;
    foreach ($this->outlets as $outlet){
        $outletdata = outlet_stock($product_id,$outlet['id']) ;
        $data = array_merge(
            array_slice($data, 0, $index + 1),
            [(!isset($outletdata) || $outletdata == 0) ? '0' : $outletdata],
            array_map(function ($machine) use ($outlet,$product_id) {
                $machinedata = machine_stock($product_id,$machine['id']);
                return (!isset($machinedata) || $machinedata == 0) ? '0' : $machinedata ;
            }, $outlet['machines']->toArray()),
            array_slice($data, $index + 1)
        );
        $index += count($outlet['machines']) + 1;
    }
        
    return $data;
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
