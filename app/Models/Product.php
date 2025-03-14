<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    use HasFactory;
    protected $fillable = [
        'user_id',
        'name',
        'description',
        'price',
        'quantity',
        'category_id',
        'status',
    ];

    public function category()
    {
        return $this->belongsTo(Category::class); //relacion uno a muchos con el modelo category
    }
    public function user()
    {
        return $this->belongsTo(User::class); //relacion uno a muchos con el modelo user
    }
    public function images(){
        return $this->hasMany(ProductImage::class);
    }
}
