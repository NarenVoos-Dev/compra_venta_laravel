<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    use HasFactory;
    protected $fillable = [
        'name',
        'description',
    ];

    public static $rules = [
        'name' => 'required|string|max:255|unique:categories,name',
        'description' => 'nullable|string'
    ];

    public function products()
    {
        return $this->hasMany(Product::class);
    }
}
