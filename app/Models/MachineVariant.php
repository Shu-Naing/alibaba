<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MachineVariant extends Model
{
    use HasFactory;
    public $fillable = [
        'id',
        'machine_id',
        'variant_id',
        'quantity',
        'created_by',
        'updated_by'
    ];

    public function variants() {
        return $this->hasMany(Variation::class,'id','variant_id');
    }
}
