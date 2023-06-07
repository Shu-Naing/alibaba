<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Variantion;

class Products extends Model
{
    use HasFactory;
    public $fillable = [
        'id',
        'item_code',
        'photo_path',
        'product_name',
        'category_id',
        'unit_id',
        'brand_id',
        'created_by',
        'updated_by'
    ];

    public function variantion() {
        return $this->hasMany(Variantion::class,'id');
    }

}
