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

    public function group() {
        return $this->belongsTo(ItemGroups::class, 'group_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'uid');
    }
}
