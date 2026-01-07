<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Keypads;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class KeypadController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $keypads = Keypads::latest()->paginate(10);

        return new Resource(true, 'List Data Keypads', $keypads);
    }
}
