<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemGroups extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'item_name',
        'description',
    ];
}
