<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Temp extends Model
{
    use HasFactory;
    protected $fillable = [
        'variation_id',
        'quantity',
        'variation_value',
        'created_by',
        'updated_by',
    ];

    public function variation()
    {
        return $this->hasOne(Variation::class,'id','variation_id');
    }
}
