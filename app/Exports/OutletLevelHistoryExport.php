<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;

class OutletLevelHistoryExport implements FromCollection, WithHeadings, WithMapping, ShouldAutoSize
{
    function __construct($histories,$outlets,$types){
        $this->histories = $histories;
        $this->outlets = $outlets;
        $this->types = $types;
    }
    public function headings(): array
    {
        $headings = [
            'Outlet',
            'Date',
            'Item Code',
            'Quantity',
            'Received/Issuced',
            'Branch',
            'Remark',
            'Check',
        ];

        return $headings;
    }


    public function collection()
    {
        return $this->histories;
    }

    public function map($history): array
    {
        $data = [
            $this->outlets[$history->outlet_id] ,
            $history->date,
            $history->item_code,
            $history->quantity,
            $this->types[$history->type],
            $history->branch,
            $history->remark,
            $history->is_check == 1 ? 'Yes' : 'No',
        ];

        return $data;
    }
}
