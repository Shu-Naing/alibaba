<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'product_name',
        'image',
        'sku',
        'quantity',
        'received_qty',
        'description',
        'brand_id',
        'category_id',
        'unit_id',
        'company_name',
        'country',
        'received_date',
        'expired_date',
        'status',
        'created_by',
        'updated_by'
       
    ];
}
