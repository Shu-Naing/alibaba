<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class distributes extends Model
{
    use HasFactory;
    public $fillable = [
        'date',
        'reference_No',
        'status',
        'from_outlet',
        'to_outlet',
        'remark',
        'created_by',
        'updated_by'
    ];

}
