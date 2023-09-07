<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Damage extends Model
{
    use HasFactory;
    public $fillable = [
        'date',
        'outlet_id',
        'name',
        'amount',
        'action',
        'error',
        'distination',
        'damage_no',                   
        'created_by',
        'updated_by',
    ];
}
