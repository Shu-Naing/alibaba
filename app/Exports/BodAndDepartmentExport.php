<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class BodAndDepartmentExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize,WithEvents
{

    function __construct($distributes,$outlets, $tooutlets){
        $this->distributes = $distributes;
        $this->outlets = $outlets;
        $this->tooutlets = $tooutlets;
    }

    public function headings(): array
    {
        $headings = [
            'Date',
            'Ref',
            'Vouncher No',
            'From Outlet',
            'To Outlet',
            'Item Code',
            'Photo',
            'Size Variant',
            'Purchased Price',
            'Qty',
            'Sub Total',
        ];

        return $headings;
    }

    public function collection()
    {
        return $this->distributes;
        
    }

    public function map($distribute): array
    {
        $data = [
           $distribute->date,
           $distribute->reference_No,
           $distribute->vouncher_no,
           $this->outlets[$distribute->from_outlet],
           $this->tooutlets[$distribute->to_outlet],
           $distribute->item_code,
            '',
           $distribute->value,
           $distribute->purchased_price,                           
           $distribute->quantity,
           $distribute->subtotal,
        ];

        return $data;
    }

    public function setImage($workSheet) {
        $this->collection()->each(function($distribute,$index) use($workSheet) {
            $drawing = new Drawing();
            $drawing->setPath(public_path('storage/'.$distribute->image));
            $drawing->setHeight(60);
            $index+=2;
            $drawing->setCoordinates("G$index");
            $drawing->setWorksheet($workSheet);
            // Get the cell range
            $cellRange = "G$index";

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
