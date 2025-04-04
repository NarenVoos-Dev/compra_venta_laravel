<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;
    protected $table = 'product_image'; // Nombre correcto de la tabla
    protected $fillable = [
        'product_id',
        'image_path',
    ];
    public function product()
    {
        return $this->belongsTo(Product::class);
    }
}
