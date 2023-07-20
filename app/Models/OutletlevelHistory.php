<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class OutletlevelHistory extends Model
{
    use HasFactory;
    public $fillable = [
        'outlet_id',
        'type',
        'quantity',
        'item_code',
        'branch',
        'date',
        'remark',
        'is_check',
        'created_by',
        'updated_by'
    ];
}
