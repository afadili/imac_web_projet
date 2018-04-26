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
    $router->get('emoji', ['uses' => 'EmojiController@showAllEmojis']);
    $router->get('emoji/{id}', ['uses' => 'EmojiController@showOneEmoji']);
    $router->get('mood', ['uses' => 'MoodController@showAllMoods']);
    $router->get('mood/{id}', ['uses' => 'MoodController@showOneMood']);
    $router->get('hashtag', ['uses' => 'HashtagController@showAllHashtags']);
    $router->get('hashtag/{id}', ['uses' => 'HahstagController@showOneHashtag']);
    $router->get('statistiques', ['uses' => 'StatistiqueController@showAllStatistiques']);
    $router->get('statistiques/{id}', ['uses' => 'StatistiqueController@showOneStatistique']);
    
    /* Méthodes POST */
    $router->post('emoji', ['uses' => 'EmojiController@create']);
    $router->post('mood', ['uses' => 'MoodController@create']);
    $router->post('hashtag', ['uses' => 'HashtagController@create']);
    $router->post('statistiques', ['uses' => 'StatistiqueController@create']);
    
    /* Méthodes DELETE */
    $router->delete('emoji/{id}', ['uses' => 'EmojiController@delete']);
    $router->delete('mood/{id}', ['uses' => 'MoodController@delete']);
    $router->delete('hashtag/{id}', ['uses' => 'StatistiqueController@delete']);
    
    /* Méthodes PUT */
    $router->put('emoji/{id}', ['uses' => 'EmojiController@update']);
    $router->put('mood/{id}', ['uses' => 'MoodController@update']);
    $router->put('hashtag/{id}', ['uses' => 'HashtagController@update']);
    $router->put('statistiques/{id}', ['uses' => 'StatistiquegController@update']);
});
