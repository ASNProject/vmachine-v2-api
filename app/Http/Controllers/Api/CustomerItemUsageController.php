<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\CustomerItemUsage;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class CustomerItemUsageController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $cis = CustomerItemUsage::latest()->paginate(10);

        return new Resource(true, 'List Data Customers Item Usage', $cis);
    }
}
