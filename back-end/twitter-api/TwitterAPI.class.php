<?php
// twitter access

require 'twitter/autoload.php'
use Abraham\TwitterOAuth\TwitterOAuth;

class TwitterAPI {

	/* ---- API AUTHENTIFICATION ---- */

	// twitter API keys
	private static $apiKey = null;
	private static $apiSecret = null;

	// twitter API token
	private static $accessToken = null;
	private static $accessTokenSecret = null;

	// set secret keys and token
	public static setAPIAccessToken($apiKey, $apiSecret, $accessToken, $accessTokenSecret) {
		$this->apiKey = $apiKey;
		$this->apiSecret = $apiSecret;
		$this->accessToken = $accessToken;
		$this->accessTokenSecret = $accessTokenSecret;
	}
}