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
			// trucate 'U+' from utf code
			$code = substr($emoji, 2);

			// build regex expression
			$regexEmoji = "/[\x{$code}]/u";

			// look for matches between regex and tweet
			preg_match($regexEmoji, $tweet->text, $matches_emo);
			if (!empty($matches_emo[0])) {
				echo $tweet->text."<br>";
				$ret[$emoji] = $tweet;
			}
		}
	}

	return $ret;
}


function startService() {
	// Wait time between two API call in seconds
	$WAIT_TIME = 0;

	// make an array of unicode codes for all emojis
	$emojis = getEmojiCodes();

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

		// wait...
		$start = time();
		while($start + $WAIT_TIME > time());
	} while (false);
}

var_dump(getEmojiChars());