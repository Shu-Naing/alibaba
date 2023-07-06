<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasedPriceHistory extends Model
{
    use HasFactory;

    protected $fillable = [
        'variation_id',
        'purchased_price',
        'points',
        'tickets',
        'kyat',
        'quantity',
        'created_by',
        'updated_by'
       
    ];

    public function variation(){
        return $this->hasONe(Variation::class,'id','variation_id');
    }
}
