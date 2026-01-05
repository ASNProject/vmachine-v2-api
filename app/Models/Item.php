<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'name',
    ];
}
