<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Statistique extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'idBatch', 'nbTweets', 'avgRetweets', 'avgFavorite', 'avgResponses', 'AvgPopularity', 'bestTweet'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}