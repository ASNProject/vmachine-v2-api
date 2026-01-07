<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerItemLimits;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class CustomerItemLimitController extends Controller
{
    /**
     * index
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $cils =  CustomerItemLimits::latest()->paginate(10);

        return new Resource(true, 'List Data Customer Item Limit', $cils);
    }
}
