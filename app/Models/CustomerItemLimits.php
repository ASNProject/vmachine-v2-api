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
        'group_id',
        'limit_qty',
        'limit_time_type',
    ];

    public function group() {
        return $this->belongsTo(ItemGroups::class, 'group_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'uid');
    }
}
