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
    $router->get('emoji', ['uses' => 'EmojiController@getAll']);
    $router->get('emoji/characters', ['uses' => 'EmojiController@getAllCharacters']);
    $router->get('emoji/U+{code}', ['uses' => 'EmojiController@getByUnicode']);
    $router->get('emoji/mood/{mood}', ['uses' => 'EmojiController@getByMoodName']);
});
