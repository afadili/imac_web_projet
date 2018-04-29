<?php 

namespace App;

use App\Emoji;
use App\TweetSamples;

/**
 * TWITTER DATA PARSER CLASS
 * -------------------------
 * Implements the TwitterDataHandler interface
 * Handles incomming data from recurrent Twitter API Service calls.
 * Calls PDO methods to insert new data into the database
 */
class TwitterDataParser {

	//// CONSTANTS

	/**
	 * Sample size, defines the requested length of twitterData before it's sent to processing stage
	 * @const SAMPLE_SIZE
	 */

	//// PROPERTIES

	/**
	 * @var Array<Tweets> $twitterData, Stores new data recieved by Twitter Service.
	 */
	private $twitterData = array();

	/**
	 * @var Array<String> Array of all the emoji characters referenced in the database.
	 */
	private $emojiList = null;



	//// HANDLER

	/**
	 * HANDLE NEW DATA
	 * Handler called by the Twitter Service
	 * execute the main job of this class:
	 * - Sort tweets by Emoji and Hashtag
	 * - Create Statistics from tweets groups
	 * - Send statistics to PDO to be inserted into the database
	 *
	 * @param Object $data, data to parse
	 */
	public function pushInBuffer($data) {		
		// if it's a tweet, push it inside the buffer
		if (!isset($data->text))
			return;
		
		array_push($this->twitterData, $data);
	}



	
	public function parseBuffer() {
		echo "Parsing ".count($this->twitterData)."tweets.\n";
		echo "New data for:\n";
		foreach (self::getTweetsGroupedByEmoji() as $emojiChar => $tweets) {
			echo "$emojiChar";
			$stats = new TweetSamples($tweets);
			//$emoji = Emoji::createFromChar($emojiChar);
			//Statistics::newDataPoint($stats, $emoji);
		}

		$logs = "";
		foreach (self::getTweetsGroupedByHashtagAndEmoji() as $emojiChar => $hashs) {
			
			foreach ($hashs as $hash => $tweets) {
				$stats = new TweetSamples($tweets);
				//$emoji = Emoji::createFromChar($emojiChar);
				//$hashtag = Hashtag::sudoCreateFromWord($hash); // if does not exist, force 

				$logs .= "#$hash\n";
				//Statistics::newDataPoint($stats, $emoji, $hashtag);
			}
		}
		echo "\n$logs\n";

		// empty sample buffer
		$this->twitterData = array();
	}


	//// GETTERS

	/**
	 * GET TWEETS GROUPED BY EMOJI 
	 * Returns a 2 dimentions array of tweets.
	 * Columns keys are emojis, grouping tweets with one or more occurences of theses emojis
	 * @return Array<String: Array<Tweet>>
	 */
	private function getTweetsGroupedByEmoji() {
		$ret = array();

		foreach ($this->twitterData as $tweet) {
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


	/**
	 * GET TWEETS GROUPED BY EMOJI AND HASHTAG
	 * Returns a 3 dimentions array of tweets.
	 * Tweets are grouped by hashtags, hashtags are grouped by emojis
	 * It's absolutely disgusting code.
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


	
	//// CONSTRUCTOR

	/**
	 * NEW TWITTER DATA PARSER
	 * Class constructor
	 * Initialise $emojiList
	 */
	public function __construct() {
		// get a list of every emoji character
		$this->emojiList = Emoji::pluck('char');
	}
	
}