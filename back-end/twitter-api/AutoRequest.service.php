<?php
require 'TwitterAPI.emoji-tracker.include.php';

class AutoRequestService {
	/* ---- PROPERTIES ---- */

	/* Parameter:
	 * integer, delay in seconds between two twitter api calls 
	 */
	private const REQUEST_DELAY = 3600;

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
	public static function start(Closure $onRequest) {
		// Set request delay
		self::setRequestDelay($delay);

		self::onRequest = $onRequest;
		
		// Set query params
		TwitterAPICall::setQueryParams('en','recent', 100);

		// make an array of all emojis
		$emojis = self::getEmojiChars();

		self::startService();
	}


	/* ---- PRIVATE METHODS ---- */

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
			sleep(self::REQUEST_DELAY);
		}
	}
	


	/* ---- CONSTRUCTOR ---- */
	private function __construct() {};
}
