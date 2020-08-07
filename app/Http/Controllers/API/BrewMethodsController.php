<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\BrewMethod;
use Illuminate\Http\Request;

class BrewMethodsController extends Controller
{
    public function getBrewMethods()
    {
        $brewMethods = BrewMethod::withCount('cafes')->get();

        return response()->json($brewMethods);
    }
}
