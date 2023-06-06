<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SellingPriceGroup extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'descriptions',
        'created_by',
        'updated_by’',
        'status'
    ];
}
