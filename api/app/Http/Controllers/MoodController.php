<?php

namespace App\Http\Controllers;

use App\Mood;
use Illuminate\Http\Request;

class MoodController extends Controller
{

    public function showAllMoods()
    {
        return response()->json(Mood::all());
    }

    public function showOneMood($id)
    {
        return response()->json(Mood::find($id));
    }

    public function create(Request $request)
    {
        $mood = Mood::create($request->all());

        return response()->json($mood, 201);
    }

    public function update($id, Request $request)
    {
        $mood = Mood::findOrFail($id);
        $mood->update($request->all());

        return response()->json($mood, 200);
    }

    public function delete($id)
    {
        Mood::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}