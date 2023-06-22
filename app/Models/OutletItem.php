<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletItem extends Model
{
    use HasFactory;
    public $fillable = [
        'outlet_id',
        'variation_id',
        'quantity',
        'created_by',
        'updated_by'
    ];

    public function variation()
    {
        return $this->hasOne(Variation::class,'id','variation_id');
    }

    public function outlet()
    {
        return $this->belongsTo(Outlets::class,'outlet_id','id');
    }
}
