<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Size extends Model
{
    use HasFactory;
    protected $fillable = ['size_ft', 'size_mm'];

    public function products()
    {
        return $this->belongsToMany(Product::class, 'product_size');
    }

    public function finishes()
    {
        return $this->belongsToMany(Finish::class);
    }
}
