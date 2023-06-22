<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
// use App\Models\Category;

class Outlets extends Model
{
    use HasFactory;
    public $fillable = [
        'outlet_id',
        'name',
        'city',
        'state',
        'category_id',
        'country',
        'created_by',
        'updated_by',
        'status',
    ];

    public function categories() {
        return $this->hasMany(Categories::class,'category_id');
    }

    public function machines() {
        return $this->hasMany(Machines::class,'outlet_id','id');
    }
}
