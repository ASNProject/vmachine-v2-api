<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Item extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'name',
        'group_id',
    ];

    public function group() {
        return $this->belongsTo(ItemGroups::class, 'group_id');
    }
}
