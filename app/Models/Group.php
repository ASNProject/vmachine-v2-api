<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Group extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'group_name',
        'products',
        'limits',
        'device_id',
        'description',
    ];

    protected $casts = [
        'products' => 'array',
    ];

    public function items() {
        return $this->hasMany(Item::class, 'group_id');
    }
}
