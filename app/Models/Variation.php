<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'variation_select',
        'variation_value',
        'purchased_price',
        'points',
        'tickets',
        'kyat',
        'variation_image',
        'size',
        'receive_quantity',
        'created_by',
        'updated_by'       
    ];

    
       

    public function product() {
        return $this->belongTo(Product::class,'product_id');
    }
}
