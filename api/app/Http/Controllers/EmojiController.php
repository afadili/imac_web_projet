<?php

namespace App\Http\Controllers;

use App\Emoji;
use App\Mood;
use App\Hashtag;

use Illuminate\Http\Request;

class EmojiController extends Controller
{
    /**
     * @return array<Emoji> all referenced emojis with details
     */
    public function getAll()
    {
        return response()->json(Emoji::all());
    }

    /**
     * @return array<String> all referenced emojis characters
     */
    public function getAllCharacters()
    {   
        return response()->json(Emoji::pluck('char'));
    }

    /**
     * @param String $needle
     * @return array<Emoji> all emojis matching needle
     */
    public function search($needle)
    {   
        $res = Emoji::where('char', 'like', $needle)
        ->orWhere('name', 'like', '%'.$needle.'%')
        ->orWhere('shortname', 'like', '%'.$needle.'%')
        ->orWhere('code', 'like', $needle.'%')
        ->orWhere('ascii', 'like', $needle.'%')
        ->orderBy('shortname')
        ->get();

        return response()->json($res);
    }


    /**
     * @param String $char Unicode code
     * @return Emoji first corresponding to char
     */
    public function getByUnicode($char)
    {
        return response()->json(Emoji::where('code', 'like', 'U+'.$char)->get()->first());
    }

    /**
    * @param String $name to search
     *@return array<Emoji> corresponding to a mood
     */
    public function getByMood($name)
    {
        return response()->json(Emoji::allFromMood(Mood::where('name', 'like',$name)->get()->first()));
    }

    /**
     * @param String $word hashtag to search
     * @return array<Emoji> corresponding to an hashtag
     */
    public function getByHashtag($word)
    {
        return response()->json(Emoji::allFromHashtag(Hashtag::where('word', 'like' ,$word)->get()->first()));
    }
}   