<?php 
require_once 'Twitter-API-Service/TwitterAPIService.emoji-tracker.include.php';
require_once 'PDO/Emoji.class.php';
require_once "TweetSamples.class.php";

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

	public function handle($data) {
		// set data
		$this->twitterData = $data;

		echo "New data for:";
		foreach (self::getTweetsGroupedByEmoji() as $emojiChar => $tweets) {
			$stats = new TweetSamples($tweets);
			$emoji = Emoji::createFromChar($emojiChar);

			echo "$emojiChar";
			Statistics::newDataPoint($stats, $emoji);
		}
		echo "\n";
	}

	/* ---- GETTERS ---- */

	// @return array<Tweets>
	private function getTweets() {
		return $this->twitterData->statuses;
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