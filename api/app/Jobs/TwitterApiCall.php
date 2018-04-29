<?php

namespace App\Jobs;

use App\TwitterDataParser;

/**
 * TWITTER API CALL
 * -------------------
 * Handles the authentification to the twitter API, connection and request.
 * Will execute a request at a set delay. 
 * New incomming data is handled through the TwitterDataHandler Interface.
 */
class TwitterAPICall extends Job
{
	/* CONSTANTS */
	
	/**
	 * Request timeout
	 * Length of request before it stops.
	 * @const Integer TIME_OUT in seconds
	 */
	const TIME_OUT = 10;

	/**
	 * Target url for api requests
	 * @const String URL
	 */
	const TARGET_URL = 'https://stream.twitter.com/1.1/statuses/sample.json';

	/**
	 * Signature method for OAuth 
	 * @const String OAUTH_SIGN_METHOD
	 */
	const OAUTH_SIGN_METHOD = 'HMAC-SHA1';

	/**
	 * Auth version used
	 * @const String OAUTH_VERSION
	 */
	const OAUTH_VERSION = '1.0a';
	
	
	
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
	 * HTTP Request Header
	 * Sent to twitter to request information
	 * Contains information for OAuth
	 * @var Object 
	 */
	private static $HTTPRequestHeader = array();


	/**
	 * Data Parser
	 * Class handling the data and parsing it for database
	 *
	 * @var TwitterDataParser $dataParser
	 */
	private $dataParser = null;
	
	
	
	/* --- METHODS --- */
	
	public function __construct() {
		echo "New parser..\n";
		$this->dataParser = new TwitterDataParser();
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
     public function handle() {
		echo "Prepare HTTP header...\n";
		self::generateRequestHeader();

		$ch = curl_init();
		curl_setopt_array($ch, array(
		    CURLOPT_URL            => self::TARGET_URL,
		    CURLOPT_SSL_VERIFYPEER => false,
		    CURLOPT_HTTPHEADER     => self::$HTTPRequestHeader,
		    CURLOPT_ENCODING       => 'gzip',
		    CURLOPT_TIMEOUT        => self::TIME_OUT,
		    CURLOPT_RETURNTRANSFER => true,
		    CURLOPT_WRITEFUNCTION => array($this, 'incommingStreamHandler')
		));
		
		echo "Send Request...\n";
		curl_exec($ch);
		curl_close($ch);
		echo "Stream Closed.\n";
		$this->dataParser->parseBuffer();
		echo "end.";
	}



    /**
     * Execute the job.
     *
     * @return void
     */
    public function incommingStreamHandler($request, $jsonData)
    {
		$data = json_decode($jsonData);
		$this->dataParser->pushInBuffer($data);
		return strlen($jsonData);
    }
	
	

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
	 * Generate HTTP Request Header
	 * Prepare a header valid for Twitter API Authentification (OAuth)
	 */
	private static function generateRequestHeader() {
		$method = 'GET';

		$oauth_params = array(
		    'oauth_consumer_key' => self::$apiKey,
		    'oauth_token' =>self::$accessToken,
			'oauth_nonce' => microtime(),
		    'oauth_timestamp' => time(),
		    'oauth_signature_method' => self::OAUTH_SIGN_METHOD,
		    'oauth_version' => self::OAUTH_VERSION
		);

		// sort oauth parameters
		$base = $oauth_params;
		uksort($base, 'strnatcmp');

		// build and encode request
		$queryURL = http_build_query($base, '', '&', PHP_QUERY_RFC3986);
		$encodedQuery = implode('&', array_map('rawurlencode', [$method, self::TARGET_URL, $queryURL]));

		// encode API private Keys
		$keys = array(self::$apiSecret, self::$accessTokenSecret);
		$encodedAuthKeys = implode('&', array_map('rawurlencode', $keys));

		// sign auth params and keys
		$oauth_params['oauth_signature'] = base64_encode(hash_hmac('sha1', $encodedQuery, $encodedAuthKeys, true));

		// encode all the things
		foreach ($oauth_params as $name => $value) {
		    $items[] = sprintf('%s="%s"', urlencode($name), urlencode($value));
		}

		// return header
		self::$HTTPRequestHeader = array('Authorization: OAuth ' . implode(', ', $items));
	}

	
}
