<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Counter extends Model
{
    use HasFactory;
    public $fillable = [
        'variant_id',
        'outlet_id',
        'name',
        'created_by',
        'updated_by'
    ];
}
