<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerItemLimits extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'uid',
        'name',
        'group_id',
        'limit_qty',
        'limit_time_type',
    ];
}
