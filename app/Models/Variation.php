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

    public function outlet_items()
    {
        return $this->hasMany(OutletItem::class);
    }

    public function machine_variant()
    {
    return $this->belongsTo(MachineVariant::class, 'variant_id', 'id');
    }
}
