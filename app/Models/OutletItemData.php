<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletItemData extends Model
{
    use HasFactory;
    protected $fillable = [
        'outlet_item_id',
        'purchased_price',
        'points',
        'tickets',
        'kyat',
        'quantity',
        'grn_no',
        'received_date',
        'country',
        'created_by',
        'updated_by'
       
    ];

    public function outlet_item()
    {
        return $this->belongsTo(OutletItem::class,'outlet_item_id','id');
    }

   
}
