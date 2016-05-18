<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use App\Models\InvalidMapping;

class InvalidMappingController extends Controller
{
    public function invalid(Request $request)
    {
        $request->flash();
        $mappings = InvalidMapping::search($request);
        return view('invalidmapping.invalid', compact('mappings'));
    }
}
