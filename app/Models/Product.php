<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'category_id'];

    public function category()
    {
        return $this->belongsTo(ProductCategory::class);
    }

    public function finishes()
    {
        return $this->belongsToMany(Finish::class, 'product_finish');
    }

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'product_size');
    }
}