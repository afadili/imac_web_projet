<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class RankingController extends Controller
{
    public function emojisBetween($param, $date_min, $date_max) {
        // protect from sql injections. since sanitisation doesnt work
        if(!in_array($param, ['usage','average_retweets','average_favorites','average_responses','average_popularity']))
        {
            return NULL;
        } 

        $sql = "
        SELECT 
            emojis.*,
            SUM(nbTweets) as `usage`, 
            AVG(avgretweets) as `average_retweets`, 
            AVG(avgfavorites) as `average_favorites`, 
            AVG(avgpopularity) as `average_responses`, 
            AVG(avgresponses) as `average_popularity`

        FROM emojis 
            JOIN relations ON emojis.id = idEmoji
            JOIN statistics ON statistics.id = idStat
            JOIN batches ON batches.id = idBatch

        WHERE batches.date BETWEEN :date_min AND :date_max

        GROUP BY emojis.id
        ORDER BY `".$param."`  DESC";

        return DB::select($sql, [':date_min'=>$date_min,':date_max'=>$date_max]);    

    }

    public function emojisSince($param, $date_min) {
        return $this->emojisBetween($param, $date_min, '9999-12-31 23:59:59');
    }

    public function emojisUntil($param, $date_max) {
        return $this->emojisBetween($param, '1970-01-01 00:00:00', $date_max);
    }

    public function emojis($param) {
        return $this->emojisBetween($param, '1970-01-01 00:00:00', '9999-12-31 23:59:59');
    }
}   