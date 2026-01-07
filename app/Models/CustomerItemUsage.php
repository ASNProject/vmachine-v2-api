<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CustomerItemUsage extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'uid',
        'item_id',
        'group_id',
        'keypad_code',
    ];
}
