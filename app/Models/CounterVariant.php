<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CounterVariant extends Model
{
    use HasFactory;
    public $fillable = [
        'id',
        'counter_id',
        'variant_id',
        'quantity',
        'created_by',
        'updated_by'
    ];
}
