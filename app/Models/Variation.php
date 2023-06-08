<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variation extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_id',
        'select',
        'value',
        'item_code',
        'received_qty',
        'alert_qty',
        'purchased_price',
        'points',
        'tickets',
        'kyat',
        'image',
        'created_by',
        'updated_by'       
    ];


    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
