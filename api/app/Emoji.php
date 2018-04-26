<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Emoji extends Model
{

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'emoji', 'name', 'shortName', 'ASCII', 'code', 'idMood'
    ];

    /**
     * The attributes excluded from the model's JSON form.
     *
     * @var array
     */
    protected $hidden = [];
}