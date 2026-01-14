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

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }

    public function group() {
        return $this->belongsTo(ItemGroups::class, 'group_id');
    }

    public function customer() {
        return $this->belongsTo(Customer::class, 'uid');
    }
}
