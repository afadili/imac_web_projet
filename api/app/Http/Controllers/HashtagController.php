<?php

namespace App\Http\Controllers;

use App\Hashtag;

// include request facade
use Illuminate\Http\Request;

/**
 * Hashtag Controller
 * ------------------
 * Handles and prepare responses for hashtag data sub-routes
 * Contains all methods related to hashtag data.
 */

class HashtagController extends Controller
{


    /**
     * All
     * 
     * dumps out all hashtags in an array
     * @return JSON: Array<String>
     */
    public function all()
    {
        return response()->json(Hashtag::all());
    }



    /**
     * Search
     *
     * return matching hashtags in an array
     * @param String $needle
     * @return JSON: Array<String>
     */
    public function search($needle)
    {
        $res = Hashtag::where('word', 'like', $needle.'%')->pluck('word');
        return response()->json($res);
    }



    /**
     * Get by emoji
     *
     * return hashtags used with an emoji
     * @param Hexadecimal value $code, Unicode
     * @return JSON: String
     */
    public function getByEmoji($code)
    {
    	// TODO
    }


}