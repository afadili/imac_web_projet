<?php

namespace App\Http\Controllers;

use App\Emoji;
use App\Mood;
use App\Hashtag;

use Illuminate\Http\Request;

class EmojiController extends Controller
{
    /**
     * @return all referenced emojis with details
     */
    public function getAll()
    {
        return response()->json(Emoji::all());
    }

    /**
     * @return all emojis matching needle
     */
    public function search($needle)
    {   
        return response()->json(Emoji::search($needle));
    }

    /**
     * @return all emojis characters
     */
    public function getAllCharacters()
    {   
        return response()->json(Emoji::allCharacters());
    }

    /**
     * @return emoji corresponding to char
     */
    public function getByUnicode($char)
    {
        return response()->json(Emoji::whereIn('code', ['U+'.$char]));
    }

    /**
     *@return emojis corresponding to a mood
     */
    public function getByMoodName($moodName)
    {
        return response()->json(Emoji::allFromMood(Mood::where('name',$moodName)));
    }
}   