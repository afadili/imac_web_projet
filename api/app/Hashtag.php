<?php

namespace App;

use Illuminate\Support\Facades\DB;
use Illuminate\Database\Eloquent\Model;

class Hashtag extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'word'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [
        'id'
    ];
	
	
	/**
	 * Sudo Get
	 * get hashtag or insert if doesn't exist
	 * @param String $word
	 * @return Hashtag
	 */
	
	public static function sudoGet($word) {
		$ret = self::where('word', 'like', $word)->first();
		
		if (empty($ret))
		{
			$ret = self::insertGetId(['word' => $word]);
			$ret = self::where('id', '=', $ret)->first();
		}
		
		return $ret;
	}
}