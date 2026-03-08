<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Device extends Model
{
    protected $fillable = [
        'device_name',
    ];

    protected $casts = [
        'group' => 'array',
    ];

    // public function groups() {
    //     return $this->hasMany(Group::class, 'device_id', 'id');
    // }
}
