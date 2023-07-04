<?php

namespace App\Exports;

use App\Models\PurchasedPriceHistory;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PurchasedPriceHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    function __construct($purchased_price_histories){
        $this->purchased_price_histories = $purchased_price_histories;
        $this->no = 1;
    }


    public function headings(): array
    {
        $headings = [
            'No',
            'Item Code',
            'Purchased Price',
            'Quantity',
            'Date',
        ];

        return $headings;

    }


    public function map($purchased_price_history): array
    {
        $data = [
             $this->no++,
             $purchased_price_history->variation->item_code,
             $purchased_price_history->purchased_price,
             $purchased_price_history->quantity,
             date('d-m-y',strtotime($purchased_price_history->created_at)),
        ];

        return $data;
    }

    public function collection()
    {
        return $this->purchased_price_histories;
    }
    
}
