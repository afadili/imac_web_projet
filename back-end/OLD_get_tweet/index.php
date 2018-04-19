<?php

// Pour acceder à la page index.php :: http://localhost/imac_web_projet/test/index.php
// clés d'accès https://apps.twitter.com/
// Library PHP https://twitteroauth.com/

//Key and Token
$apikey = 'Z0arj4wYR0TlRIcpdy4tdAvIR';
$apisecret = '3px8MvckKG4TpaOuUysLK4j4mndgpvsfoXHxQeG3ZjfBwK3YDR';
$accesstoken = '325628241-MkdJ62Jy2wVC12yt8jsQQdzwTR8nqnTHB1mAmDIV';
$accesstokensecret = 'uaMveJiLbGvN4TKIBp1UdNVdyTYJS8Iabh0CMs3CZnLuY';

// Include Library
require_once 'hasemoji.php';
require_once 'gethashtag.php';
require 'twitter/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

// Connect to API
$connection = new TwitterOAuth($apikey, $apisecret, $accesstoken, $accesstokensecret);
$content = $connection->get("account/verify_credentials");

//Search and Get Tweets

// Récupère les tweets de ma timeline
//$tweets = $connection->get("statuses/home_timeline");

// Get tweets where "e" appears
//I create a fake random
$subject = "e";
$tweets= $connection->get("search/tweets", ["q" => $subject, "lang" => "fr", "result_type" => "recent", "count" =>100]);
//print_r($tweets);
getBatch($tweets);


// GET TRENDING TOPICS IN FRANCE

//$tweets = $connection->get("trends/place",["id" => "23424819"]);
//print_r($tweets);

// Lien pour obtenir les géocodes des pays/villes... http://woeid.rosselliot.co.nz/lookup/france
// (1) programme qui regarde si il y a des émojis dans une string : https://gist.github.com/hnq90/316f08047a3bf348b823
// source émoji interessante http://graphemica.com/


// Programme qui regarde si il y a des émoji à partir de (1) compléter avec https://apps.timwhitlock.info/emoji/tables/unicode#block-6c-other-additional-symbols


?>
