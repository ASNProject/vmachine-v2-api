<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    protected $fillable = [
        'product_name',
        'keypad',
        'description',
    ];

    protected $casts = [
        'products' => 'array',
    ];
}
