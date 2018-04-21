<?php 
require_once "TwitterAPIService.class.php";
require_once '../data/Emoji.class.php';


class TwitterDataParser implements TwitterDataHandler {

	private $twitterData = null;
	private $emojiList = null;

	public function __construct() {
		// get a list of every emoji character
		$this->emojiList = array_map(
			function($e) { return $e->getEmoji(); }, 
			Emoji::getAll()
		);
	}

	public function newRequestHandler($data) {
		// set data
		$this->twitterData = $data;

		require_once "TweetSamples.class.php";

		foreach (self::getTweetsGroupedByEmoji() as $emojiChar => $tweets) {
			$stats = new TweetSamples($tweets);
			$emoji = Emoji::createFromChar($emojiChar);

			Statistics::newDataPoint($stats, $emoji);
		}
	}

	/* ---- GETTERS ---- */

	// @return array<Tweets>
	private function getTweets() {
		return $this->twitterData->statuses;
	}

	// @returns array<String> an array of Hashtags
	private function getHashtags() {
		return array_map(
			$this->twitterData->entities->hashtags,
			function($h) {return $h->text;}
		);
	}

	/* Get Tweets grouped by Emoji
	 * Returns a 2 dimentions array of tweets.
	 * Columns keys are emojis, grouping tweets with one or more occurences of theses emojis
	 * @return Array<String => Array<Tweet>>
	 */
	private function getTweetsGroupedByEmoji() {
		$ret = array();

		foreach ($this->twitterData->statuses as $tweet) {
			foreach ($this->emojiList as $emojiChar) {
				// checks if emoji is in tweet.
				if (mb_stripos($tweet->text, $emojiChar, 0, "UTF-8")) {
						if (isset($ret[$emojiChar]))
							array_push($ret[$emojiChar], $tweet);
						else 
							$ret[$emojiChar] = array($tweet);
				}
			}
		}

		return $ret;
	}
}