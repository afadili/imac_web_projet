<?php 
require_once 'Twitter-API-Service/TwitterAPIService.emoji-tracker.include.php';
require_once 'PDO/Emoji.class.php';
require_once 'PDO/Hashtag.class.php';
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

		echo "New data for:\n";
		foreach (self::getTweetsGroupedByEmoji() as $emojiChar => $tweets) {
			echo "$emojiChar";
			$stats = new TweetSamples($tweets);
			$emoji = Emoji::createFromChar($emojiChar);
			Statistics::newDataPoint($stats, $emoji);
		}

		$logs = "";
		foreach (self::getTweetsGroupedByHashtagAndEmoji() as $emojiChar => $hashs) {
			
			foreach ($hashs as $hash => $tweets) {
				$stats = new TweetSamples($tweets);
				$emoji = Emoji::createFromChar($emojiChar);
				$hashtag = Hashtag::sudoCreateFromWord($hash);

				$logs .= "#$hash ";
				Statistics::newDataPoint($stats, $emoji, $hashtag);
			}
		}
		echo "\n$logs\n";
	}

	/* ---- GETTERS ---- */

	// @return array<Tweets>
	private function getTweets() {
		return $this->twitterData->statuses;
	}

	/* Get Tweets grouped by Emoji
	 * Returns a 2 dimentions array of tweets.
	 * Columns keys are emojis, grouping tweets with one or more occurences of theses emojis
	 * @return Array<String: Array<Tweet>>
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

	/* Get Tweets grouped by Emoji and Hashtag
	 * Returns a 3 dimentions array of tweets.
	 * Tweets are grouped by hashtags, hashtags are grouped by emojis
	 * It's absolutely disgusting code.
	 *
	 * @return Array<String: Array<String: Array<Tweet>>>
	 */
	private function getTweetsGroupedByHashtagAndEmoji() {
		$ret = $this->getTweetsGroupedByEmoji();

		foreach ($ret as $emoji => $tweets) {
			$hashs = array();
			foreach ($tweets as $tweet) {
				foreach ($tweet->entities->hashtags as $hashtag) {
					if (isset($hashs[$hashtag->text]))
							array_push($hashs[$hashtag->text], $tweet);
						else 
							$hashs[$hashtag->text] = array($tweet);
				}
			}
			$ret[$emoji] = $hashs;
		}

		return $ret;
	}
}