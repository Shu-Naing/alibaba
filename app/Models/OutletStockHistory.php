<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletStockHistory extends Model
{
    use HasFactory;

    public $fillable = [
        'outlet_id',
        'machine_id',
        'type',
        'quantity',
        'variant_id',
        'branch',
        'date',
        'remark',
        'created_by',
        'updated_by'
    ];
}
