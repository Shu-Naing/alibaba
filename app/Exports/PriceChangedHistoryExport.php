<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class PriceChangedHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    function __construct($price_changed_histories){
        $this->price_changed_histories = $price_changed_histories;
    }

    public function headings(): array
    {
        $headings = [
            'Item Code',
            'Purchased Price',
            'Point',
            'Ticket',
            'Kyat',
            'Received Date'
        ];

        return $headings;

    }


    public function collection()
    {
        return $this->price_changed_histories;
    }


    public function map($price_changed_history): array
    {
        $data = [
            $price_changed_history->item_code,
            $price_changed_history->purchased_price,
            $price_changed_history->points,
            $price_changed_history->tickets,
            $price_changed_history->kyat,
            $price_changed_history->received_date,
        ];

        return $data;
    }
}
