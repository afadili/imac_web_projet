<?php
require 'TwitterAPI.emoji-tracker.include.php';

class AutoRequestService {
	/* ---- PROPERTIES ---- */

	/* Parameter:
	 * integer, delay in seconds between two twitter api calls 
	 */
	private static $REQUEST_DELAY = 3600;

	// stores api call results
	private static $apiCall = null;

	// Closure handling every new request
	private static $onRequest = null;



	/* ---- INIT ---- */

	/* Start Service
	 * Initialise variables, twitter API
	 * launch infinite loop
	 * launch infinite loop
	 * launch infinite loop
	 *
	 * @param Closure handling new api results
	 */
	public static function start(Closure $onRequest, $delay = 3600) {
		// Set request delay
		self::setRequestDelay($delay);

		self::onRequest = $onRequest;
		
		// Set query params
		TwitterAPICall::setQueryParams('en','recent', 100);

		// make an array of all emojis
		$emojis = self::getEmojiChars();

		self::startService();
	}



	/* ---- SETTERS ---- */	

	/* Set request delay, in seconds, waited between twitter API requests.
  	 * @param: time in seconds 
	 */
	public static function setRequestDelay($seconds) { 
		self::$REQUEST_DELAY = $seconds;
	}



	/* ---- GETTERS ---- */

	/* Get Tweets grouped by Emoji
	 * Returns a 2 dimentions array of tweets.
	 * Columns keys are emojis, grouping tweets with one or more occurences of theses emojis
	 * @return Array<String => Array<Tweet>>
	 */
	public static function getTweetsGroupedByEmoji() {
		$ret = array();
		foreach (self::$apiCall->getTweets() as $t) {
			foreach ($emojis as $e) {
				// checks if emoji is in tweet.
				if (mb_stripos($t->text, $e, 0, "UTF-8")) {
					$ret[$e] = array_merge($ret[$e],array($t));
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

	
	/* Send New Request:
	 * Establish connection to the twitter API and sends a query.
	 * Should not be called too often, API access is limited.
	 * @changes self::$apiCall with new data.
	 */
	private static function newRequest() {
		TwitterAPICall::connect();
		self::$apiCall = new TwitterAPICall();
	}


	/* Start Service:
	 * Starts the loop and calls a new request every so often
	 * - Requests intervalls are set with setRequestDelay()
	 * never @returns
	 */
	private static function startService() {
		while (true) {
			self::newRequest();
			sleep(self::$REQUEST_DELAY);
		}
	}
	


	/* ---- CONSTRUCTOR ---- */
	private function __construct() {};
}
