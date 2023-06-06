<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DistributeProducts extends Model
{
    use HasFactory;
    public $fillable = [
        'id',
        'distribute_id',
        'variant_id',
        'quantity',
        'purchased_price',
        'subtotal',
        'remark',
        'created_by',
        'updated_by'
    ];
}
