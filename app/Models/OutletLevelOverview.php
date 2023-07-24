<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletLevelOverview extends Model
{
    use HasFactory;
    public $fillable = [
        'date',
        'opening_qty',
        'receive_qty',
        'issued_qty',
        'outlet_id',
        'item_code',
        'balance',
        'is_check',
        'physical_qty',
        'difference_qty',
        'created_by',
        'updated_by',
    ];
}
