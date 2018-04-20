<?php
require 'TwitterAPI.emoji-tracker.include.php';
require '../data/Emoji.class.php';

function getEmojiCodes() {
	$emojis = array();
	foreach (Emoji::getAll() as $emoji)
		$emojis = array_merge($emojis, explode(' ', $emoji->getCode()));
	return array_unique($emojis);
}

function getEmojiChars() {
	return array_map(function($e) {
		return $e->getEmoji();
	}, Emoji::getAll());
}


function sortByEmojis($tweetArray, $emojiCodes) {
	$ret = array();

	// Turn emojiCodes array into a regex expressions array
	$emojiRegexs = array_map(
		function($c) {
			return '/*\\'.substr($c, 2).'*/'; //u for unicode
		}, 
		$emojiCodes
	);


	// scan tweets for each code
	foreach ($tweetArray as $tweet) {
		foreach ($emojiRegexs as $key=>$regex) {
			if (preg_match($tweet->text,$regex));
				echo "found $regex";
		}
		echo 'no emojis in'.$tweet->text."<br>";
	}

	return $ret;
}


function startService() {
	// Wait time between two API call in seconds
	$WAIT_TIME = 0;

	// make an array of all emojis
	$emojis = getEmojiChars();

	// Set query params
	TwitterAPICall::setQueryParams('en','recent',100);

	// lauch server
	do {
		// prepare an array for sorted tweets
		$tweetsSortedByEmojis = array();

		// establish connection to tweeter API
		TwitterAPICall::connect();

		// get tweets from the API call
		$tweets = (new TwitterAPICall())->getTweets();
		
		//filter out tweets with no emojis
		foreach ($tweets as $t) {
			foreach ($emojis as $e) {
				if (mb_stripos($t->text, $e, 0, "UTF-8")) {
					if (isset($tweetsSortedByEmojis[$e])) {
						array_push($tweetsSortedByEmojis[$e], $t);
					} else {
						$tweetsSortedByEmojis[$e] = [$t];
					}
				}
			}
		}

		var_dump(array_keys($tweetsSortedByEmojis));

		// wait...
		$start = time();
		while($start + $WAIT_TIME > time());
	} while (false);
}

startService();

