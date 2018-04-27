<?php

namespace App\Http\Controllers;

use App\Hashtag;
use Illuminate\Http\Request;

class HashtagController extends Controller
{
    public function all()
    {
        return response()->json(Hashtag::all());
    }

    public function search($needle)
    {
        $res = Hashtag::where('word', 'like', $needle.'%')->pluck('word');
        return response()->json($res);
    }
}