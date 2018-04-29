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

    // RANKING 
    $router->group(['prefix' => 'ranking'], function() use ($router) {
    	$router->get('/by_{method}',['uses' => 'RankingController@emojis']);
    	$router->get('/by_{method}/since_{date}',['uses' => 'RankingController@emojisSince']);
    	$router->get('/by_{method}/until_{date}',['uses' => 'RankingController@emojisUntil']);
    	$router->get('/by_{method}/between_{date}',['uses' => 'RankingController@emojisBetween']);
    });

    // HISTORIC
    $router->group(['prefix' => 'history'], function() use ($router) {
    	$router->get('/U+{code}/by_{method}', ['uses' => 'HistoryController@emojiStatOverTime']);
    });

    // EMOJI 
    $router->group(['prefix' => 'emoji'], function() use ($router) {
    	$router->get('/', ['uses' => 'EmojiController@getAll']);
    	$router->get('/search/{needle}', ['uses' => 'EmojiController@search']);
    	$router->get('/characters', ['uses' => 'EmojiController@getAllCharacters']);
    	$router->get('/U+{code}', ['uses' => 'EmojiController@getByUnicode']);
    	$router->get('/bymood/{mood}', ['uses' => 'EmojiController@getByMood']);
    	$router->get('/byhashtag/{word}', ['uses' => 'EmojiController@getByHashtag']);
    });

    // STATISTICS
    $router->group(['prefix' => 'statistics'], function() use ($router) { 
    	$router->get('/for_U+{code}', ['uses' => 'StatisticsController@getFromEmoji']);
    	$router->get('/for_U+{code}/and_#{tag}', ['uses' => 'StatisticsController@getFromEmojiAndHashtag']);
    });

    // HASHTAG
    $router->group(['prefix' => 'hashtag'], function() use ($router) {
	    $router->get('/', ['uses' => 'HashtagController@all']);
	    $router->get('/search/{word}', ['uses' => 'HashtagController@search']);
	    $router->get('/for_U+{code}', ['uses' => 'HashtagController@getByEmoji']);
	});

    // MOOD
    $router->group(['prefix' => 'mood'], function() use ($router) {
	    $router->get('/', ['uses' => 'MoodController@all']);
	    $router->get('/for_U+{code}', ['uses' => 'MoodController@getByEmoji']);
	});
});
