<?php
require_once "PDO/Statistics.class.php";

/**
 * TWEET SAMPLES CLASS
 * -----------------------------------
 * implements StatisticsData interface
 * Set of functions to analyse a set of tweets.
 */

class TweetSamples implements StatisticsData {
	
	//// PROPERTIES

	/**
	 * @var array<Tweets>, statistic samples to analyse
	 */
	private $tweets;


	
	//// GETTERS

	/**
	 * Count
	 * @return Integer size of the sample of tweets
	 */
	public function count() {
		return count($this->tweets);
	}


	/** 
	 * Best
	 * @return String URL of the most popular tweet of the sample
	 */
	public function best() {
		$best = null;
		$max = -1;
		foreach ($this->tweets as $tweet) {
			if($max <= $tweet->retweet_count){
				$best = $tweet;
				$max = $tweet->retweet_count;
			}
		}

		return "https://www.twitter.com/statuses/{$best->id}";
	}


	// TOTALS

	/**
	 * Total Retweet Count
	 * @return Integer sum of all the retweets of each tweet the sample
	 */
	public function totalRetweetCount() {

		return array_sum(array_map( 
			function($tweet) {
				if (isset($tweet->retweet_count))
					return $tweet->retweet_count;
				else 
					return 0;
			},
			$this->tweets
		));
	}


	/**
	 * Total Favorite Count
	 * @return Interger sum of all the favorites of each tweet the sample
	 */
	public function totalFavoriteCount() {
		return array_sum(array_map( 
			function($tweet) {
				if (isset($tweet->favorite_count))
					return $tweet->favorite_count;
				else 
					return 0;
			},
			$this->tweets
		));
	}


	/**
	 * Total Response Count
	 * @return Interger sum of all the replies of each tweet the sample
	 */
	public function totalResponseCount() {
		return array_sum(array_map( 
			function($tweet) {
				if (isset($tweet->reply_count))
					return $tweet->reply_count;
				else 
					return 0;
			},
			$this->tweets
		));
	}


	// AVERAGES

	/**
	 * Average Retweets
	 * @return Float average of retweets per tweet in the sample
	 */
	public function avgRetweets() {
		return $this->totalRetweetCount() / $this->count();
	}


	/**
	 * Average Favorite
	 * @return Float average of favorite per tweet in the sample
	 */
	public function avgFavorites() {
		return $this->totalFavoriteCount() / $this->count();
	}


	/**
	 * Average Responses
	 * @return Float average of responses per tweet in the sample
	 */
	public function avgResponses() {
		return $this->totalResponseCount() / $this->count();
	}

	/**
	 * Average Popularity
	 * @return Float average popularity of a tweet in the sample
	 */
	public function avgPopularity() {
		return $this->avgResponses() + $this->avgFavorites() + $this->avgResponses();
	}



	//// CONSTRUCTOR

	/**
	 * Constructor
	 * @param array<Tweets>
	 * Array is expected to not be empty
	 */
	public function __construct(array $tweets) {
		$this->tweets = $tweets;

		if (self::count() <= 0)
			throw new Error("Empty array of tweets");
	}
}