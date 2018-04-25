<?php

namespace App\Http\Controllers;

use App\Emoji;
use Illuminate\Http\Request;

class EmojiController extends Controller
{

    public function showAllEmojis()
    {
        return response()->json(Emoji::all());
    }

    public function showOneEmoji($id)
    {
        return response()->json(Emoji::find($id));
    }

    public function create(Request $request)
    {
        $emoji = Emoji::create($request->all());

        return response()->json($emoji, 201);
    }

    public function update($id, Request $request)
    {
        $emoji = Emoji::findOrFail($id);
        $emoji->update($request->all());

        return response()->json($emoji, 200);
    }

    public function delete($id)
    {
        Emoji::findOrFail($id)->delete();
        return response('Deleted Successfully', 200);
    }
}