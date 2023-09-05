<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage_items extends Model
{
    use HasFactory;
    public $fillable = [
        'item_code',
        'ticket',
        'amount_ks',
        'quantity',
        'original_cost',
        'created_by',
        'updated_by',
    ];
}
