<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LimitPeriods extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'uid',
        'group_id',
        'period_month',
        'remaining_qty',
    ];
}
