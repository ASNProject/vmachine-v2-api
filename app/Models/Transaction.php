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

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function product() {
        return $this->belongsTo(Product::class);
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'uid', 'uid');
    }
}
