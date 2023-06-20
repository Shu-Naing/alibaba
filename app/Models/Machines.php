<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Machines extends Model
{
    use HasFactory;
    public $fillable = [
        'outlet_id',
        'name',
        'created_by',
        'updated_by'
    ];

    public function machine_variants()
    {
        return $this->hasMany(MachineVariant::class, 'machine_id', 'id');
    }
}
