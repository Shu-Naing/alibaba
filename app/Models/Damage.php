<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;
    public $fillable = [
        'date',
        'voucher_no',
        'outlet_id',
        'item_code',
        'description',
        'quantity',
        'ticket',
        'original_cost',
        'amount_ks',
        'reason',
        'name',
        'amount',
        'action',
        'error',
        'distination',
        'damage_no',
        'column1',
        'created_by',
        'updated_by'
    ];
}
