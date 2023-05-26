<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Outlets extends Model
{
    use HasFactory;
    public $fillable = [
        'outlet_id',
        'name',
        'city',
        'state',
        'country',
        'created_by',
        'updated_by',
    ];
}
