<?php

namespace App\Http\Controllers;

use App\Hashtag;
use Illuminate\Http\Request;

class HashtagController extends Controller
{

    public function showAllHashtags()
    {
        return response()->json(Hashtag::all());
    }

    public function showOneHashtag($id)
    {
        return response()->json(Hashtag::find($id));
    }

    public function create(Request $request)
    {
        $hashtag = Hashtag::create($request->all());

        return response()->json($hashtag, 201);
    }

    public function update($id, Request $request)
    {
        $hashtag = Hashtag::findOrFail($id);
        $hashtag->update($request->all());

        return response()->json($hashtag, 200);
    }

    public function delete($id)
    {
        Hashtag::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}