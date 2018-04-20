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
				if (mb_stripos($tweet->text, $e, 0, "UTF-8")) {
					$ret[$emojiChar] = array_merge($ret[$emojiChar],array($tweet));
				}
			}
		}

		return $ret;
	}


	// @returns integer the number of tweets retrieved
	private function getTweetCount() {
		return count($this->twitterData->statuses);
	}


	// @returns integer the total number of retweet through all tweets
	private function getTotalRetweetsCount() {
		return array_sum(array_map(
			$this->twitterData->statuses, 
			function($t) {return $t->retweet_count;}
		));
	}

	// @returns integer the total number of favorites through all tweets
	private function getTotalFavoriteCount() {
		return array_sum(array_map(
			$this->twitterData->statuses, 
			function($t) {return $t->favorite_count;}
		));
	}


	// @return float average of retweets per tweet
	private function getAverageRetweetsCount() {
		return $this->getTotalRetweetsCount() / $this->getTweetCount();
	}

	// @return float average of Favorite per tweet
	private function getAverageFavoriteCount() {
		return $this->getTotalFavoriteCount() / $this->getTweetCount();
	}
}