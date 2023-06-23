<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletDistributeProduct extends Model
{
    use HasFactory;
    public $fillable = [
        'id',
        'outlet_distribute_id',
        'variant_id',
        'quantity',
        'purchased_price',
        'subtotal',
        'remark',
        'created_by',
        'updated_by'
    ];

    public function outlet_distribute()
    {
        return $this->belongto(OutletDistribute::class);
    }
}
