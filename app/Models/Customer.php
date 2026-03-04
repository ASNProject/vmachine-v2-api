<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Customer extends Model
{
    protected $primaryKey = 'uid';
    public $incrementing = false;
    protected $keyType = 'string';
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
        'status',
        'limits',
        'limit_group_device',
    ];

    /**
     * Relation To Role
     */
    public function role()
    {
        return $this->belongsTo(Role::class);
    }

    protected $casts = [
        'limit_group_device' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'uid';
    }
}
