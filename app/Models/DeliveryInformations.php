<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DeliveryInformations extends Model
{
    use HasFactory;

    protected $fillable = [
        'township',
        'price',        
    ];
}
