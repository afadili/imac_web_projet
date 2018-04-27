<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Emoji extends Model
{
    // disable created_at/updated_at columns
    public $timestamps = false;

    protected $table = 'emojis';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'char', 'name', 'shortName', 'ASCII', 'code'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id','idMood'];


    /**
     * @return list of emoji related to a mood
     */
    static public function allFromMood($mood) 
    {
        if (empty($mood)) return NULL;
        return self::whereRaw('idMood IN (SELECT id FROM moods WHERE id = ?)', [$mood->id])->get();
    }

    /**
     * @return the list of emoji used with a referenced hashtag
     */
    static public function allFromHashtag($hashtag) 
    {
        if (empty($hashtag)) return NULL;
        return self::whereRaw('id IN (SELECT idEmoji FROM relations WHERE idHashtag = ?)', [$hashtag->id])->get();
    }
}