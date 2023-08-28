<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Variation;

class Product extends Model
{
    use HasFactory;

    protected $fillable = [
        'product_name',
        'product_code',
        'description',
        'brand_id',
        'category_id',
        'unit_id',
        'company_name',
        'country',
        'received_date',
        'expired_date',
        'status',
        'created_by',
        'updated_by'
       
    ];

    public function variations()
    {
        return $this->hasMany(Variation::class);
    }

    public function brand()
    {
        return $this->belongsTo(Brands::class);
    }

    public function category()
    {
        return $this->belongsTo(Categories::class);
    }

    public function unit()
    {
        return $this->belongsTo(Units::class);
    }
}
