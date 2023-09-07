<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DamageItems extends Model
{
    use HasFactory;
    public $fillable = [
        'damage_id',
        'item_code',
        'ticket',
        'quantity',
        'point',
        'kyat',
        'purchase_price',
        'total',
        'reason',
        'created_by',
        'updated_by',
    ];
}
