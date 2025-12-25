# Laravel MongoDB Integration Guide

This document outlines the steps taken to successfully integrate MongoDB into the Airbnb Explorer application, including configuration, common pitfalls, and solution patterns.

## 1. Installation & Dependencies

### Package Requirement
Ensure you are using the official MongoDB package for Laravel. The deprecated `jenssegers/mongodb` package is **incompatible** with modern Laravel versions for this setup.

**Correct Package:**
```bash
composer require mongodb/laravel-mongodb
```

**Remove Deprecated Package:**
```bash
composer remove jenssegers/mongodb
```

## 2. Configuration

### Environment Variables (`.env`)
Ensure your connection string and database name are correct.
```dotenv
DB_CONNECTION=mongodb
MONGODB_URI=mongodb+srv://<username>:<password>@cluster0.mongodb.net/?retryWrites=true&w=majority
MONGODB_DATABASE=sample_airbnb
```

### Database Config (`config/database.php`)
Ensure the `mongodb` driver is defined in your `connections` array:
```php
'mongodb' => [
    'driver' => 'mongodb',
    'dsn' => env('MONGODB_URI'),
    'database' => env('MONGODB_DATABASE'),
],
```

## 3. Creating Models

To create a MongoDB-backed model, extend the correct class and specify the **table** (which maps to a MongoDB collection).

### Vital Property: `$table` vs `$collection`
> [!IMPORTANT]
> **Trap:** In previous versions (jenssegers), the property was `$collection`.
> **Fix:** In the official `mongodb/laravel-mongodb` package, you must use **`protected $table`** just like a SQL model.

**Example: `app/Models/ListingAndReviews.php`**
```php
<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ListingAndReviews extends Model
{
    protected $connection = 'mongodb';
    
    // Maps to the 'listingsAndReviews' collection in MongoDB
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
```

## 4. Debugging History & Solutions

### Issue 1: "Class Not Found"
**Symptom:** `Class "ListingAndReviews" not found` in Tinker.
**Cause:** Mismatch between the installed package (`jenssegers`) and the namespace used in the model (`MongoDB\Laravel\Eloquent\Model`).
**Solution:** Swapped to the official package and regenerated autoload files (`composer dump-autoload`).

### Issue 2: "Invisible Data" (0 Records Found)
**Symptom:** Connection successful, database correct (`sample_airbnb`), but `count()` returned `0`.
**Cause:** The model defaulted to expecting a snake_case collection `listing_and_reviews`. We set `protected $collection`, but the new driver ignored it.
**Solution:** Changed `protected $collection` to `protected $table = 'listingsAndReviews'`.

## 5. Usage Examples

### Basic Retrieval
```php
use App\Models\ListingAndReviews;

// Get first listing
$listing = ListingAndReviews::first();
echo $listing->name;

// Find listings with price < 200
$cheapStays = ListingAndReviews::where('price', '<', 200)->get();
```

### Working with Embedded Data
MongoDB allows nested documents (like `address` object).
```php
// Query nested fields using dot notation
$miamiListings = ListingAndReviews::where('address.city', 'Miami')->get();
```

### Aggregation (Advanced)
```php
$rawParams = [
    [
        '$group' => [
            '_id' => '$property_type',
            'count' => ['$sum' => 1]
        ]
    ]
];
$stats = ListingAndReviews::raw(function($collection) use ($rawParams) {
    return $collection->aggregate($rawParams);
});
```
