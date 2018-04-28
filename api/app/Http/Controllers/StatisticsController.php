<?php

namespace App\Http\Controllers;

use App\Statistics;

use App\Emoji;
use App\Hashtag;

use Illuminate\Http\Request;

// TODO : Documentation
class StatisticsController extends Controller
{
    public function getBatch($date)
    {
        // TODO or DELETE
    }

    // TODO: ADD MINMAX DATE
    public function getFromEmoji($code) {
        return response()->json(Statistics::whereEmoji($code)->get());
    }

    // TODO: ADD MINMAX DATE
    public function getFromEmojiAndHashtag($code,$hashtag) {
        return response()->json(Statistics::whereEmojiAndHashtag($code,$hashtag)->get);
    }
}