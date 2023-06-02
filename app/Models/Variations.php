<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Variations extends Model
{
    use HasFactory;

    protected $fillable = [
        'variation_select',
        'purchase_price',
        'points',
        'updated_by’',
        'tickets',
        'kyat',
        'variation_image'
    ];
}
