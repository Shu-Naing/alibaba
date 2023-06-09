<?php

namespace App\Exports;

use Maatwebsite\Excel\Events\AfterSheet;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithDrawings;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProductsExport implements FromCollection, WithHeadings,WithMapping,WithDrawings,ShouldAutoSize
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }

    public function collection()
    {
        return collect($this->data);
    }

    public function map($data): array
    {
        return [
            $data->id,
            $data->item_code,
            // Modify the path below based on the actual location of your images
            // asset('storage/' . $data->image),
            $this->drawings()
            // $data->image
        ];
    }

    public function headings(): array
    {
        return [
            'ID',
            'Item_code',
            'Image',
        ];
    }

 
   

    public function drawings()
    {
        
        $drawings = [];
        foreach($this->data as $key=>$product)
        {
            $drawing = new Drawing();
            $drawing->setPath(public_path('storage/'.$product->image));
            $drawing->setHeight(40);
            $drawing->setWidth(50);
            $drawing->setCoordinates('C'.($key+1));
            $drawings [] = ($drawing);
        }
        return $drawings;
    }

    // public function registerEvents(): array
    // {
    //     return [
    //         AfterSheet::class    => function(AfterSheet $event) {
    //             $event->sheet->getDelegate()->getRowDimension('1')->setRowHeight(40);
    //             $event->sheet->getDelegate()->getColumnDimension('A')->setWidth(50);
     
    //         },
    //     ];
    // }
}
