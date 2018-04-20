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

	// scan tweets for each code
	foreach ($tweetArray as $tweet) {
		foreach ($emojiCodes as $emoji) {
			$ret[$emoji] = array();
			if (strstr($tweet->text, $emoji))
				array_push($ret[$emoji], $tweet);
		}
	}

	return $ret;
}


function startService() {
	// Wait time between two API call in seconds
	$WAIT_TIME = 0;

	// make an array of unicode codes for all emojis
	$emojis = getEmojiChars();

	// Set query params
	TwitterAPICall::setQueryParams('en','recent',100);

	// lauch server
	do {
		// establish connection to tweeter API
		TwitterAPICall::connect();

		// get tweets from the API call
		$tweets = (new TwitterAPICall())->getTweets();

		// sort by emojis
		$tweetsByEmojis = sortByEmojis($tweets, $emojis);
		var_dump($tweetsByEmojis);

		// wait...
		$start = time();
		while($start + $WAIT_TIME > time());
	} while (false);
}
startService();