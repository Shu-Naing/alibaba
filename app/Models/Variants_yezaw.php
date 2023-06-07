<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variants extends Model
{
    use HasFactory;
    public $fillable = [
        'product_id',
        'points',
        'tickets',
        'sale_price',
        'size',
        'receive_quantity'
    ];

    public function product() {
        return $this->belongTo(Product::class,'product_id');
    }
}

