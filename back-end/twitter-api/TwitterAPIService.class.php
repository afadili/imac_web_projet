<?php

// TwitterOAuth library:
require 'twitter/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;

/* TWITTER API SERVICE
 *
 *
 */

class TwitterAPIService {
	
	/* ---- CONSTANTS ---- */
	// must be false on production
	const DEBUG = true;

	// integer, delay in seconds between two twitter api calls 
	private static $REQUEST_DELAY = 0;

	// query parameters
	private static $REQUEST_SUBJECT = 'e';
	private static $REQUEST_REGION_CODE = 'en';
	private static $REQUEST_RESULT_TYPE = 'recent';
	private static $REQUEST_RETULTS_COUNT = 100;


	

	/* ---- PROPERTIES ---- */

	// twitter API keys
	// TODO: (documentation) qu'est-ce qu'une apiKey?
	private static $apiKey = null;
	private static $apiSecret = null;

	// twitter API token
	// TODO: (documentation) qu'est-ce qu'un accessToken?
	private static $accessToken = null;
	private static $accessTokenSecret = null;

	// active api connection
	private static $connection;

	// Object implementing TwitterDataHandler
	// handles every new request
	private static $requestHandler;

	private static $data;


	

	/* --- METHODS --- */

	// set secret keys and token
	public static function setAPIAccessTokens($apiKey, $apiSecret, $accessToken, $accessTokenSecret) {
		self::$apiKey = $apiKey;
		self::$apiSecret = $apiSecret;
		self::$accessToken = $accessToken;
		self::$accessTokenSecret = $accessTokenSecret;
	}


	/* Start Service
	 * Initialise variables, twitter API
	 * launch infinite loop and never returns
	 *
	 * @param Closure handling new api results
	 */
	public static function start(TwitterDataHandler $onRequest) {
		self::$requestHandler = $onRequest;

		do {
			self::newRequest();
			sleep(self::$REQUEST_DELAY);
		} while(!self::DEBUG);
	}


	/* Send New Request:
	 * Establish connection to the twitter API and sends a query.
	 * Should not be called too often, API access is limited.
	 * @changes self::$apiCall with new data.
	 */
	private static function newRequest() {
		self::$connection = new TwitterOAuth(
			self::$apiKey, 
			self::$apiSecret, 
			self::$accessToken, 
			self::$accessTokenSecret
		);
		
		self::$data = self::$connection->get(
			"search/tweets", 
			[
				"q" => self::$REQUEST_SUBJECT, 
				"lang" => self::$REQUEST_REGION_CODE, 
				"result_type" => self::$REQUEST_RESULT_TYPE, 
				"count" => self::$REQUEST_RETULTS_COUNT
			]
		);

		self::$requestHandler->newRequestHandler(self::$data);
	}



	/* ---- GETTERS ----- */

	// @returns array<String> an array of Hashtags
	public function getHashtags() {
		return array_map(
			$this->entities->hashtags,
			function($h) {return $h->text;}
		);
	}

	// @return array<Tweets>
	public function getTweets() {
		return $this->tweets->statuses;
	}


	/* ---- CONSTRUCTOR ---- */
	private function __construct() {}

};



/* Twitter Data Handler Interface
 *
 */

interface TwitterDataHandler {
	public function newRequestHandler($data);
}
