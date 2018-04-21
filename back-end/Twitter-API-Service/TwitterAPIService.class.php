<?php

/** 
 * TwitterOAuth library:
 */
require 'twitter/autoload.php';
use Abraham\TwitterOAuth\TwitterOAuth;


/**
 * TWITTER API SERVICE
 * -------------------
 * Handles the authentification to the twitter API, connection and request.
 * Will execute a request at a set delay. 
 * New incomming data is handled through the TwitterDataHandler Interface.
 */

class TwitterAPIService {
	
	/* CONSTANTS */
	
	/**
	 * Debug Flag
	 * disables request loop.
	 * must be false on production 
	 * @const Boolean
	 */
	const DEBUG = false;
	
	/**
	 * Defines request interval in seconds (default to 1hour)
	 * @const Integer
	 */
	const REQUEST_DELAY = self::DEBUG ? 0 : 120;


	
	/* REQUEST PARAMETERS */

	/**
	 * @var String Keyword searched 
	 */
	private static $REQUEST_SUBJECT = 'e';

	/**
	 * @var String Language code (default: english)
	 */
	private static $REQUEST_LANGUAGE_CODE = 'en';

	/**
	 * @var String Result sorting method (default: recent)
	 */
	private static $REQUEST_RESULT_TYPE = 'recent';

	/**
	 * @var Integer Result count (maximum: 100)
	 */
	private static $REQUEST_RETULTS_COUNT = 100;


	
	/* API AUTHENTIFICATION VARIABLES */

	/**
	 *  API authentification keys
	 * 	TODO : document auth key.
	 * 
	 * 	@var String apiKey, apiSecret
	 */
	private static $apiKey = null;
	private static $apiSecret = null;

	/**
	 *  API access tokens
	 * 	TODO : document access Token.
	 * 
	 * 	@var String accessToken, accessTokenSecret
	 */
	private static $accessToken = null;
	private static $accessTokenSecret = null;

	
	/**
	 * API connection
	 * TODO: document this thing 
	 *
	 * @var Object 
	 */
	private static $connection;


	/**
	 * Request handler
	 * Object implementing the TwitterDataHandler interface
	 * Handles new data following requests. 
	 *
	 * @var TwitterDataHandler requestHandler
	 */
	private static $requestHandler;


	

	/* --- METHODS --- */

	/** 
	 * Set API access tokens:
	 * Initialise the Twitter API Authentification variables
	 *
	 * @param String $apiKey, $apiSecret, $accessToken, $accessTokenSecret
	 */
	public static function setAPIAccessTokens($apiKey, $apiSecret, $accessToken, $accessTokenSecret) {
		self::$apiKey = $apiKey;
		self::$apiSecret = $apiSecret;
		self::$accessToken = $accessToken;
		self::$accessTokenSecret = $accessTokenSecret;
	}


	/** 
	 * Start Service :
	 * Initialise variables and  twitter API.
	 * Start an infinite loop and never returns: 
	 * The loop calls a new request and sleep 
	 * for a set delay before the next iteration.
	 *
	 * @param TwitteerDataHandler $onRequest
	 */
	public static function start(TwitterDataHandler $onRequest) {
		self::$requestHandler = $onRequest;

		do {
			echo "[".date("Y-m-d H:i:s")."]\nSend new request to Twitter API...\n";
			$data = self::newRequest();

			echo "Parse data...\n";
			self::$requestHandler->handle($data);

			echo "Done!\n\n";
			
			// stream logs 
			ob_end_flush(); 
		    flush(); 
		    if(ob_get_contents()) ob_flush();
		    ob_start(); 

			sleep(self::REQUEST_DELAY);
		} while(!self::DEBUG);
	}


	/** 
	 * Send New Request:
	 * Establish connection to the twitter API and sends a query.
	 * Should not be called too often, API access is limited.
	 *
	 * @changes self::$apiCall with new data.
	 */
	private static function newRequest() {
		self::$connection = new TwitterOAuth(
			self::$apiKey, 
			self::$apiSecret, 
			self::$accessToken, 
			self::$accessTokenSecret
		);
		
		return self::$connection->get(
			"search/tweets", 
			[
				"q" => self::$REQUEST_SUBJECT, 
				"lang" => self::$REQUEST_LANGUAGE_CODE, 
				"result_type" => self::$REQUEST_RESULT_TYPE, 
				"count" => self::$REQUEST_RETULTS_COUNT
			]
		);
	}


	// privatise constructor
	private function __construct() {}
};



/* 
 * Twitter Data Handler Interface
 * An object must comply to this interface in order to 
 * collect data from the Twitter API Service
 */

interface TwitterDataHandler {
	public function handle($data);
}
