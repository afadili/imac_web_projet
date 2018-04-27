<?php

namespace App\Http\Controllers;

use App\Mood;
use Illuminate\Http\Request;

class MoodController extends Controller
{
    public function all()
    {
        return response()->json(Mood::all());
    }

    public function search($needle)
    {
    	$res = Mood::where('name', 'like', $needle.'%')->pluck('name');
        return response()->json($res);
    }
}