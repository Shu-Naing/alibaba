<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Outlets;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = [ 'category_name','category_code', 'description', 'create_by', 'outlet_id'];

    public function outlet()
    {
        return $this->belongsTo(Outlets::class, 'outlet_id');
    }


}
