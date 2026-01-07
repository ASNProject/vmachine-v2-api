<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\ItemGroups;
use App\Http\Resources\Resource;
use Illuminate\Support\Facades\Validator;

class ItemGroupController extends Controller
{
    /**
     * index 
     * 
     * @return void
     */
    public function index()
    {
        // get all data
        $itemgroup = ItemGroups::latest()->paginate(10);

        return new Resource(true, 'List Data Item Group', $itemgroup);
    }
}
