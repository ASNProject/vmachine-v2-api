<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Keypads extends Model
{
    /**
     * fillable
     * 
     * @var array
     */
    protected $fillable = [
        'keypad_code',
        'item_id',
    ];

    public function item() {
        return $this->belongsTo(Item::class, 'item_id');
    }
}
