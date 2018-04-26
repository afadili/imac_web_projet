<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emoji extends Model
{
    // disable created_at/updated_at columns
    public $timestamps = false;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emoji', 'name', 'shortName', 'ASCII', 'code'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = ['id','idMood'];

     /**
     * search emoji by char, name, shortname, ASCII or unicode
     * @return emoji list
     */
    public function search($needle) 
    {
        // prepare wildcard params:
        $params = [
            ':same' => $needle,
            ':beginby' => '%'.$needle,
            ':inside' => '%'.$needle.'%'
        ];

        // Squirrel query
        $sql = 'SELECT * FROM emoji
            WHERE emoji LIKE :same
            OR name LIKE :inside
            OR shortname LIKE :beginby
            OR ASCII LIKE :same
            OR unicode LIKE :inside
            ORDER BY name
        ';

        // execute 
        $res = Emoji::select($query, $params);
        return response()->json($res);
    }

    /**
     * get all the characters without any information
     * @return array of character
     */
    static public function allCharacters() {
        self::all()->pluck('emoji');
    }

    /**
     * @return list of emoji related to a mood
     */
    static public function allFromMood($mood) 
    {
        $query = 'SELECT * FROM emoji WHERE idMood IN (SELECT id FROM mood WHERE id = ?)';
        $res = Emoji::select($query, array($mood->get('id')));
        return response()->json($res);
    }

    /**
     * @return the list of emoji used with a referenced hashtag
     */
    public function allFromHashtag($hashtaf) 
    {
        $query = 'SELECT * FROM emoji WHERE id IN (SELECT idEmoji FROM relation WHERE idHashtag = ?)';
        $res = Emoji::select($query, array($hashtag->get('id')));
        return response()->json($res);
    }
}