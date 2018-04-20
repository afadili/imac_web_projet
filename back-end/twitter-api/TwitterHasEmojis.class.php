<?php 
require_once "TwitterAPI.class.php";

class TwitterHasEmojis extends TwitterAPI {

	/* ---- GETTERS ---- */

	/* Get Tweets grouped by Emoji
	 * Returns a 2 dimentions array of tweets.
	 * Columns keys are emojis, grouping tweets with one or more occurences of theses emojis
	 * @return Array<String => Array<Tweet>>
	 */
	public static function getTweetsGroupedByEmoji() {
		$ret = array();
		$emojis = self::getEmojiChars();

		foreach (self::$tweets->statuses as $tweet) {
			foreach ($emojis as $emojis) {
				// checks if emoji is in tweet.
				if (mb_stripos($tweet->text, $e, 0, "UTF-8")) {
					$ret[$emojis] = array_merge($ret[$emojis],array($tweet));
				}
			}
		}

		return $ret;
	}



	/* ---- PRIVATE METHODS ---- */

	/* Get Emoji Characters:
	 * Returns an array of every emojis character referenced in the databases.
	 * Emojis can be a string of 2 or more characters long (ex: region codes).
	 * @returns array<Strings>
	 */
	private static function getEmojiChars() {
		require_once '../data/Emoji.class.php';
		
		return array_map(function($e) {
			return $e->getEmoji();
		}, Emoji::getAll());
	}
	
}