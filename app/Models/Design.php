<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Design extends Model
{
    use HasFactory;
    protected $fillable = ['name'];

    public function sizes()
    {
        return $this->belongsToMany(Size::class, 'design_size_finish');
    }

    public function finishes()
    {
        return $this->belongsToMany(Finish::class, 'design_size_finish');
    }

    public function products()
    {
        return $this->belongsToMany(Product::class, 'design_size_finish');
    }
}
