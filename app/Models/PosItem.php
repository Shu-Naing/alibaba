<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PosItem extends Model
{
    use HasFactory;
    protected $fillable = [
        'pos_id',
        'variation_id',
        'quantity',
        'variation_value',
       
    ];

    public function variation()
    {
        return $this->hasOne(Variation::class,'id','variation_id');
    }

    public function pos()
    {
        return $this->belongsTo(Pos::class,'pos_id','id');
    }
}
