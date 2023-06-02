<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Products extends Model
{
    use HasFactory;

    protected $fillable = [
        'item_code',
        'product_id',
        'companyname',
        'product_name',
        'country',
        'unit',
        'brand'
    ];
}
