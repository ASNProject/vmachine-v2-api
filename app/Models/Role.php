<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'description',
    ];
}
