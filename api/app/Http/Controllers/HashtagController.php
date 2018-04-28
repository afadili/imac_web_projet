<?php

namespace App\Http\Controllers;

use App\Hashtag;
use Illuminate\Http\Request;

class HashtagController extends Controller
{
	// TODO : Documentation
    public function all()
    {
        return response()->json(Hashtag::all());
    }

    // TODO : Documentation
    public function search($needle)
    {
        $res = Hashtag::where('word', 'like', $needle.'%')->pluck('word');
        return response()->json($res);
    }

    // TODO : Documentation
    public function getByEmoji($code) {
    	// TODO
    }
}