<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pos extends Model
{
    use HasFactory;
    protected $fillable = [
        'invoice_no',
        'total',
        'payment_type',
        'created_by',
        'updated_by'       
    ];

    public function pos_items()
    {
        return $this->hasMany(PosItem::class,'pos_id');
    }
}
