<?php

namespace App\Http\Controllers;

use App\Statistics;

use App\Emoji;
use App\Hashtag;

use Illuminate\Http\Request;

/**
 * Ranking Controller
 * ------------------
 * Handles and prepare responses for raw stastistics data
 */
class StatisticsController extends Controller
{   

    /**
     * Get Batch
     *
     * Get statistics from batch closest to given date
     *
     * @param $date
     * @return JSON: Array<Satistics> 
     */
    public function getBatch($date)
    {
        // TODO or DELETE
    }

 
    
    /**
     * Get From Emoji
     *    
     * Get raw statistics about an emoji
     *
     * @param Hexadecimal $code, Unicode
     * @return JSON: Array<Statistics>
     */
    public function getFromEmoji($code) {
        return response()->json(Statistics::whereEmoji($code)->get());
    }

    /**
     * Get From Emoji And Hashtag
     *    
     * Get raw statistics about an emoji in context of an hashtag
     *
     * @param Hexadecimal $code, Unicode
     * @param String $hashtag
     *
     * @return JSON: Array<Statistics>
     */
    public function getFromEmojiAndHashtag($code,$hashtag) {
        return response()->json(Statistics::whereEmojiAndHashtag($code,$hashtag)->get);
    }
}