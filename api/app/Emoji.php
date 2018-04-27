<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
    static public function search($needle) 
    {
        return DB::table('emoji')
        ->where('emoji', 'like', $needle)
        ->orWhere('name', 'like', '%'.$needle.'%')
        ->orWhere('shortname', 'like', '%'.$needle.'%')
        ->orWhere('code', 'like', $needle.'%')
        ->orWhere('ascii', 'like', $needle.'%')
        ->orderBy('shortname')
        ->get();
    }



    /**
     * get all the characters without any information
     * @return array of character
     */
    static public function allCharacters() {
        return DB::table('emoji')->pluck('emoji');
    }

    /**
     * @return list of emoji related to a mood
     */
    static public function allFromMood($mood) 
    {
        $query = 'SELECT * FROM emoji WHERE ';
        return DB::table('emoji')->whereRaw('idMood IN (SELECT id FROM mood WHERE id = ?)', [$mood->get('id')]);
    }

    /**
     * @return the list of emoji used with a referenced hashtag
     */
    public function allFromHashtag($hashtag) 
    {
        $query = 'SELECT * FROM emoji WHERE id IN (SELECT idEmoji FROM relation WHERE idHashtag = ?)';
        $res = Emoji::select($query, array($hashtag->get('id')));
        return response()->json($res);
    }
}