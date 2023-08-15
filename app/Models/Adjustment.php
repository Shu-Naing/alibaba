<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Adjustment extends Model
{
    use HasFactory;

    protected $fillable = [
        'adj_no',
        'date',  
        'outlet_id',   
        'item_code',   
        'adjustment_qty',
        'remark',
        'type',
        'created_by',
        'updated_by',
    ];
}
