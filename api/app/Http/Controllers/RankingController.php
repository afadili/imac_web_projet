<?php

namespace App\Http\Controllers;

// include database facade
use Illuminate\Support\Facades\DB;

// include request facade
use Illuminate\Http\Request;


/**
 * Ranking Controller
 * ------------------
 * Handles and prepare responses for ranking sub-routes
 * Contains all methods related to ranked data, such as 'most used emoji', 
 * or 'most retweeted emoji' etc...
 */
class RankingController extends Controller
{


    /**
     * Emojis Between 
     *
     * Return an ordered list of emojis given a ranking parameter, and time braket.
     *
     * @param String $param, ranking parameter, must be in: ['usage','average_retweets','average_favorites','average_responses','average_popularity']
     * @param String $date_min, minimum date
     * @param String $date_max, maximum date
     *
     * @return String JSON response
     *   -> {
     *          'data': Array[ {Emoji emoji, Int usage, ... , Float average_popularity} ],
     *          'totals': {Int usage, ... , Float average_popularity}
     *      }
     */
    public function emojisBetween($param, $date_min, $date_max) {
        
        $validParams = ['usage','average_retweets','average_favorites','average_responses','average_popularity'];

        // protect from sql injections. since sanitisation doesnt work
        if(!in_array($param, $validParams))
        {
            return NULL;
        } 


        // prepare SQL query
        $sql = "
            SELECT 
                emojis.*,
                SUM(nbTweets) as `usage`, 
                AVG(avgretweets) as `average_retweets`, 
                AVG(avgfavorites) as `average_favorites`, 
                AVG(avgpopularity) as `average_responses`, 
                AVG(avgresponses) as `average_popularity`,
                bestTweet

            FROM emojis 
                JOIN relations ON emojis.id = idEmoji
                JOIN statistics ON statistics.id = idStat
                JOIN batches ON batches.id = idBatch

            WHERE batches.date BETWEEN :date_min AND :date_max

            GROUP BY emojis.id
            ORDER BY `".$param."`  DESC";

        // execute
        $results = DB::select($sql, [':date_min'=>$date_min,':date_max'=>$date_max]);

        
        // group columns from the emoji table in an emoji sub-array
        // and compute totals for each params
        $formatedResults = [];
        foreach ($results as $result) {
            $new = ['emoji'=>[]];
            foreach ($result as $key => $value) {
                if(!in_array($key, $validParams))
                {
                    $new['emoji'][$key] = $value;
                }
                else
                {
                    $new[$key] = $value;
                }
            }
            array_push($formatedResults,$new);
        }

        return $formatedResults;
    }



    /**
     * Emojis Since 
     *
     * Alias for Emoji Between 
     *
     * @param String $param, ranking parameter, must be in: ['usage','average_retweets','average_favorites','average_responses','average_popularity']
     * @param String $date_min, minimum date
     * @return String JSON response
     */
    public function emojisSince($param, $date_min) {
        return $this->emojisBetween($param, $date_min, '9999-12-31 23:59:59');
    }



    /**
     * Emojis Until 
     *
     * Alias for Emoji Between 
     *
     * @param String $param, ranking parameter, must be in: ['usage','average_retweets','average_favorites','average_responses','average_popularity']
     * @param String $date_max, maximum date
     * @return String JSON response
     */
    public function emojisUntil($param, $date_max) {
        return $this->emojisBetween($param, '1970-01-01 00:00:00', $date_max);
    }



    /**
     * Emojis 
     *
     * Alias for Emoji Between 
     *
     * @param String $param, ranking parameter, must be in: ['usage','average_retweets','average_favorites','average_responses','average_popularity']
     * @return String JSON response
     */
    public function emojis($param) {
        return $this->emojisBetween($param, '1970-01-01 00:00:00', '9999-12-31 23:59:59');
    }


}   