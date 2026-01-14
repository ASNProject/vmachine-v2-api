<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ItemGroups extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'group_name',
        'description',
    ];

    public function items() {
        return $this->hasMany(Item::class, 'group_id');
    }
}
