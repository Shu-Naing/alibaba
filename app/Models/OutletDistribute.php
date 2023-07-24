<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletDistribute extends Model
{
    use HasFactory;
    public $fillable = [
        'date',
        'reference_No',
        'status',
        'from_outlet',
        'to_machine',
        'remark',
        'counter_machine',
        'store_customer',
        'type',
        'created_by',
        'updated_by'
    ];

    public function outlet_distribute_products()
    {
        return $this->hasMany(OutletDistributeProduct::class);
    }
}
