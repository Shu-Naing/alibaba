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
        'description',
        'reason',
        'name',
        'amount',
        'action',
        'error',
        'distination',
        'damage_no',
        'column1',                    
        'created_by',
        'updated_by',
    ];
}
