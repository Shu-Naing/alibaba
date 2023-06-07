<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'pos_id',
        'product_id',
        'quantity',
        'product_value',
       
    ];
}
