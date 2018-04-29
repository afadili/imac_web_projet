<?php

namespace App\Http\Controllers;

use App\Mood;

// include request facade
use Illuminate\Http\Request;


/**
 * Mood Controller
 * ------------------
 * Handles and prepare responses for mood data sub-routes
 * Contains all methods related to mood data.
 */
class MoodController extends Controller
{


	/**
     * All
     * 
     * dumps out all moods in an array
     * @return JSON: Array<String>
     */
    public function all()
    {
        return response()->json(Mood::pluck('name'));
    }



	/**
     * Search
     *
     * return matching moods in an array
     * @param String $needle
     * @return JSON: Array<String>
     */
    public function search($needle)
    {
    	$res = Mood::where('name', 'like', $needle.'%')->pluck('name');
        return response()->json($res);
    }



    /**
     * Get by emoji
     *
     * return mood linked to an emoji
     * @param Hexadecimal value $code, Unicode
     * @return JSON: String
     */
    public function getByEmoji($code)
    {
    	// TODO
    }

    
}