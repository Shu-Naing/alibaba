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
}
