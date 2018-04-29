<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
    // PROPERTIES

    /**
     * table name in database
     * @var String
     */
    protected $table = 'statistics';


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idBatch', 'nbTweets', 'avgRetweets', 'avgFavorite', 'avgResponses', 'avgPopularity', 'bestTweet'
    ];



    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id'];



    /**
     * Where Emoji (is)
     *
     * Get collection of statisitics about an emoji
     *
     * @param Hexadecimal Value $code identifing a referenced emoji
     * @return Collection < Statistics >
     */
    private static function whereEmoji($code) {
        $idEmoji = Emoji::where('code', 'like', '%U+'.$code.'%')->get()->first()->id;
        $sql = '
            id IN (
                SELECT idStat FROM relations
                WHERE idEmoji = ? AND idHashtag IS NULL
            )';

        return Statistics::whereRaw($sql, [$idEmoji])
    }



    /**
     * Where Emoji (is ... ) and Hashtag (is ...)
     *
     * Get collection of statisitics about an emoji used with an hashtag
     *
     * @param Hexadecimal Value $code identifing a referenced emoji
     * @param String $hashtag
     *
     * @return Collection < Statistics >
     */
    public static function whereEmojiAndHastag($code,$hashtag){
        $idEmoji = Emoji::where('code', 'like', '%U+'.$code.'%')->get()->first()->id;
        $idHashtag = Hashtag::where('word', 'like', $hashtag)->get()->first()->id;

        $sql = '
            id IN (
                SELECT idStat FROM relations
                WHERE idEmoji = :emoji AND idHashtag = :hash
            )';

        return Statistics::whereRaw($sql, [':emoji' => $idEmoji, ':hash' => $idHashtag]);
    }


    /**
     * Grab [NOT WORKING]
     *
     * Get collection of statisitics given params
     *
     * @param Array $params of parameters
     *      => 'emoji': Hex value
     *      => 'hashtag': String
     *      => 'min_date': date braket start
     *      => 'max_date': date braket end
     *
     * @return Collection < Statistics >
     */
    public static function grab(array $params) {

        // TODO

        if(isset($params['emoji']))
        {
            if(!isset($params['hashtag']))
            {
                $query = Statistics::whereEmoji($params['emoji']);
            }
            else
            {
                $query = Statistics::whereEmojiAndHastag($params['emoji'],$params['hashtag']);
            }
        }

        if(isset($param['since']))
        {
            $query->addWhere()
        }
    }
}