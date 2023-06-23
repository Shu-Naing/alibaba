<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;



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

    public function distribute_porducts()
    {
        return $this->hasMany(DistributeProducts::class,'distribute_id');
    }
    public function distribute_porducts_variants(): HasManyThrough
    {
        return $this->hasManyThrough(Variation::class, DistributeProducts::class,'distribute_id','id');
    }

}
