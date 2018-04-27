<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistics extends Model
{
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

    private static function whereEmoji($code) {
        $idEmoji = Emoji::where('code', 'like', '%U+'.$code.'%')->get()->first()->id;
        $sql = '
            id IN (
                SELECT idStat FROM relations
                WHERE idEmoji = ? AND idHashtag IS NULL
            )';

        return Statistics::whereRaw($sql, [$idEmoji])
    }

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

    public static function grab(array $params) {
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