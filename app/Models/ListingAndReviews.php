<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ListingAndReviews extends Model
{
    protected $connection = 'mongodb';
    
    protected $table = 'listingsAndReviews';
 
    protected $fillable = [
        'name', 'summary', 'property_type', 'bedrooms', 'beds',
        'bathrooms', 'price', 'address', 'amenities'
    ];
 
    protected $casts = [
        'bedrooms' => 'integer',
        'bathrooms' => 'decimal:1',
        'price' => 'decimal:2',
    ];
}
