<?php

/*
|--------------------------------------------------------------------------
| Application Routes
|--------------------------------------------------------------------------
|
| Here is where you can register all of the routes for an application.
| It is a breeze. Simply tell Lumen the URIs it should respond to
| and give it the Closure to call when that URI is requested.
|
*/

$router->get('/', function () use ($router) {
    return view('hello');
});

/* Chaque route aura un préfixe /api */
$router->group(['prefix' => 'api'], function() use ($router) {
    
    /* Méthodes GET */

    // EMOJI 
    $router->group(['prefix' => 'emoji'], function() use ($router) {
    	$router->get('/', ['uses' => 'EmojiController@getAll']);
    	$router->get('/search/{needle}', ['uses' => 'EmojiController@search']);
    	$router->get('/characters', ['uses' => 'EmojiController@getAllCharacters']);
    	$router->get('/U+{code}', ['uses' => 'EmojiController@getByUnicode']);
    	$router->get('/bymood/{mood}', ['uses' => 'EmojiController@getByMood']);
    	$router->get('/byhashtag/{word}', ['uses' => 'EmojiController@getByHashtag']);
    });

    // HASHTAG
    $router->get('hashtag/', ['uses' => 'HashtagController@all']);
    $router->get('hashtag/search/{word}', ['uses' => 'HashtagController@search']);
    $router->get('hashtag/byemoji/{code}', ['uses' => 'HashtagController@getByEmoji']);

    $router->get('mood/', ['uses' => 'MoodController@all']);
    $router->get('mood/byemoji/{code}', ['uses' => 'MoodController@getByEmoji']);
});
