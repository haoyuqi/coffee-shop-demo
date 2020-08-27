<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use App\Models\Tag;
use Illuminate\Http\Request;

class TagsController extends Controller
{
    public function getTags(Request $request)
    {
        $query = $request->input('search', '');

        if (blank($query)) {
            $tags = Tag::all();
        } else {
            $tags = Tag::where('name', 'LIKE', $query . '%')->get();
        }

        return response()->json($tags);
    }
}
