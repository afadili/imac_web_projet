<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RankingController extends Controller
{

    public function emojis($param) {
        // protect from sql injections. since sanitisation doesnt work
        if(!in_array($param, ['usage','avgerage_retweets','avgerage_favorite','avgerage_responses','avgerage_popularity'])) 
            return NULL;
        else
            $sql = '
            SELECT 
            emojis.*,
            SUM(nbTweets) as `usage`, 
            AVG(avgretweets) as `avgerage_retweets`, 
            AVG(avgfavorites) as `avgerage_favorite`, 
            AVG(avgpopularity) as `avgerage_responses`, 
            AVG(avgresponses) as `avgerage_popularity`

            FROM emojis 
            JOIN relations ON emojis.id = idEmoji
            JOIN statistics ON statistics.id = idStat

            GROUP BY emojis.id
            ORDER BY `'.$param.'`  DESC;
        ';

        return DB::select($sql);
    }
}   