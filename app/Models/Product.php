<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\Review;
use App\Models\Image;

class Product extends Model
{
    protected $fillable = [
        'id',
        'title',
        'description',
        'category',
        'price',
        'discountPercentage',
        'rating',
        'stock',
        'tags',
        'brand',
        'sku',
        'weight',
        'width',
        'height',
        'depth',
        'warrantyInformation',
        'shippingInformation',
        'availabilityStatus',
        'returnPolicy',
        'minimumOrderQuantity',
        'barcode',
        'qrCode',
        'thumbnail',
        'created_at',
        'updated_at',
    ];

    public function reviews()
    {
        return $this->hasMany(Review::class);
    }

    public function images()
    {
        return $this->hasMany(Image::class);
    }
    
    
}
