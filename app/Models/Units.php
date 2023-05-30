<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Units extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'allow_decimal',
        'created_by',
        'updated_by’',
        'status'
    ];
}
