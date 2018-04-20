<?php
require_once "TwitterAPI.class.php";

class TwitterStatistics extends TwitterAPICall {

	/* ---- GET TOTALS ---- */

	// @returns integer the number of tweets retrieved
	public function getTweetCount() {
		return count($this->tweets->statuses);
	}


	// @returns integer the total number of retweet through all tweets
	public function getTotalRetweetsCount() {
		return array_sum(array_map(
			$this->tweets, 
			function($t) {return $t->retweet_count;}
		));
	}

	// @returns integer the total number of favorites through all tweets
	public function getTotalFavoriteCount() {
		return array_sum(array_map(
			$this->tweets, 
			function($t) {return $t->favorite_count;}
		));
	}


	/* ----- GET AVERAGES -----*/

	// @return float average of retweets per tweet
	public function getAverageRetweetsCount() {
		return $this->getTotalRetweetsCount() / $this->getTweetCount();
	}

	// @return float average of Favorite per tweet
	public function getAverageFavoriteCount() {
		return $this->getTotalFavoriteCount() / $this->getTweetCount();
	}
}