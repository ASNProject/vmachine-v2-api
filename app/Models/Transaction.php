<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    protected $fillable = [
        'uid',
        'device_name',
        'group_id',
        'product_id',
    ];
}
