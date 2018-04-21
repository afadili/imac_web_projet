<?php
require_once "PDO/Statistics.class.php";

class TweetSamples implements StatisticsData {
	
	private $tweets;

	// @param array<Tweets>
	public function __construct($tweets) {
		$this->tweets = $tweets;
		
		if (!is_array($this->tweets))
			throw new Error("Expected parameter to be an array");


		if (self::count() <= 0)
			throw new Error("Empty array of tweets");
	}

	// @returns integer the number of tweets retrieved
	public function count() {
		return count($this->tweets);
	}

	// todo : return best tweet:
	public function best() {
		return false;
	}

	// @returns integer the total number of retweet through all tweets
	public function totalRetweetCount() {

		return array_sum(array_map( 
			function($tweet) {return $tweet->retweet_count;},
			$this->tweets
		));
	}

	// @returns integer the total number of favorites through all tweets
	public function totalFavoriteCount() {
		return array_sum(array_map( 
			function($tweet) {return $tweet->favorite_count;},
			$this->tweets
		));
	}


	// @return float average of retweets per tweet
	public function avgRetweets() {
		return $this->totalRetweetCount() / $this->count();
	}

	// @return float average of Favorite per tweet
	public function avgFavorites() {
		return $this->totalFavoriteCount() / $this->count();
	}

	public function avgResponses() {
		return 0;
	}

	public function avgPopularity() {
		return 0;
	}
}