<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable =[
        'uid',
        'name',
        'phone_number',
        'role_id',
    ];

    /**
     * Relation To Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }
}
