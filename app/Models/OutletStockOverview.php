<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletStockOverview extends Model
{
    use HasFactory;
    public $fillable = [
        'date',
        'opening_qty',
        'receive_qty',
        'issued_qty',
        'outlet_id',
        'machine_id',
        'item_code',
        'balance',
        'created_by',
        'updated_by',
    ];
    
}
