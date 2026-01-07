<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\LimitPeriods;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class LimitPeriodController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $limitp = LimitPeriods::latest()->paginate(10);

        return new Resource(true, 'List Data Limit Periods', $limitp);
    }
}
