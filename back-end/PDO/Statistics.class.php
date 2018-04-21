<?php
require_once 'PDO/MyPDO/MyPDO.emoji-tracker.include.php'; 

/**
 * STATISTICS CLASS
 * Represent the 'Statistics' table of the database
 */

class Statistics {

	//// PROPERTIES

	/**
	 * @var Integer $id, id of the group of statistics
	 */
	private $id = null;

	/**
	 * @var Integer $nbTweets, number of tweets in the sample
	 */
	private $nbTweets = null;

	/**
	 * @var Float $avgRetweets, average retweets of a sampled tweet
	 */
	private $avgRetweets = null;

	/**
	 * @var Float $avgFavorites, average favorites of a sampled tweet
	 */
	private $avgFavorites = null;

	/**
	 * @var Float $avgResponses, average responses of a sampled tweet
	 */
	private $avgResponses = null;

	/**
	 * @var Float $avgPopularity, average popularity of a sampled tweet
	 */
	private $avgPopularity = null;

	/**
	 * @var String $bestTweet, url of the most popular tweet of the sample
	 */
	private $bestTweet = null;



	
	//// BASIC GETTERS

	/** 
	 * GET $ID 
	 * @return  Integer $id
	 */
	public function getId() { 
		return $this->id; 
	}

	
	/** 
	 * GET NBTWEETS 
	 * @return Integer $nbTweets
	 */
	public function getNbTweets() {
		return $this->nbTweets;
	}
	
	/** 
	 * GET AVGRETWEETS 
	 * @return Integer $avgRetweets
	 */
	public function getAvgRetweets() {
		return $this->avgRetweets;
	}
	
	/** 
	 * GET AVGFAVORITES 
	 * @return Integer $avgFavorites
	 */
	public function getAvgFavorites() {
		return $this->avgFavorites;
	}
	
	/** 
	 * GET AVGRESPONSES 
	 * @return Integer $avgResponses
	 */
	public function getAvgResponses() {
		return $this->avgResponses;
	}
	
	/** 
	 * GET AVGPOPULARITY 
	 * @return Integer $avgPopularity
	 */
	public function getAvgPopularity() {
		return $this->avgPopularity;
	}
	
	/** 
	 * GET BESTTWEET 
	 * @return Integer $bestTweet
	 */
	public function getBestTweet() {
		return $this->bestTweet;
	}
	



	//// FACTORIES
	
	/**
	 * CREATE FROM ID
	 * @param Integer $id, id of the Stat to fetch from the database
	 * @return Statistics instance
	 * @throws Exception if id is not referenced
	 */
	public static function createFromId($id) {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM statistics WHERE id = ?");
		$stmt->bindValue(1, $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Statistics");
		if (($object = $stmt->fetch()) !== false)
			return $object;
		else
			throw new Exception("\$id does not match any referenced statistics in database.");
	}




	//// COMPLEX GETTERS

	/**
	 * GET BATCH
	 * return the batch containing the statistics
	 * batch gives info about the date of the sampling and groups stats together
	 * @return Batch instance
	 */
	public function getBatch() {
		require_once "Batch.class.php";

		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM batch WHERE id = ?");
		$stmt->execute(array($this->idBatch));
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Batch");

		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Batch id:$this->idBatch does not exists.");
	}


	/**
	 * GET ALL STATS FROM BATCH
	 * Grabs all the statistics in a batch
	 * @param Integer $idbatch, id of the stats group
	 * @return array<Statistics>
	 */
	public static function getFromBatch($idBatch) {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM statistics WHERE idBatch = ?");
		$stmt->execute(array($idBatch));
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Statistics");

		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Batch id:$idBatch does not exists.");
	}


	/**
	 * GET ALL STATS FROM EMOJI
	 * Grabs all the statistics linked with an emoji
	 * Does not include statistics linked with an hashtag (see getFromEmojiHashtag)
	 * @param Integer $idEmoji, id of the emoji
	 * @return array<Statistics>
	 */

	public static function getFromEmoji($idEmoji) {
		$query = "
			SELECT * FROM statistics
			WHERE id IN (
				SELECT idStat FROM relation
				WHERE idEmoji = ? AND idHashtag = NULL
			)
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute(array($idEmoji));
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Statistics");

		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Emoji id:$idEmoji does not exists.");
	}


	/**
	 * GET ALL STATS FROM EMOJI AND HASHTAG
	 * Grabs all the statistics linked with an emoji and an hashtag
	 * @param Integer $idEmoji id of the emoji 
	 * @param Integer $idHashtag id of the hashtag
	 * @return array<Statistics>
	 */

	public static function getFromEmojiHashtag($idEmoji, $idHashtag) {
		$query = "
			SELECT * FROM statistics
			WHERE id IN (
				SELECT idStat FROM relation
				WHERE idEmoji = ? AND idHashtag = ?
			)
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute(array($idEmoji, $idHashtag));
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Statistics");

		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Emoji id:$idEmoji or Hashtag id:$idHashtag does not exists.");
	}


	/** 
	 * GET LATEST
	 * @return Statistic Instance, instance of the last inserted statistic.
	 */
	private static function getLatest() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM statistics WHERE id = LAST_INSERT_ID()");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Statistics");
		if (($obj = $stmt->fetch()) !== false) {
			return $obj;
		} else {
			throw new Exception("Failed to get last stat");
		}
	}



	//// SETTERS

	/**
	 * NEW DATA POINT
	 * Insert new statistics and binds it with an emoji and optionaly an Hashtag
	 * @param StatisticsData $data, data object with the stats to push to the database
	 * @param Emoji $emoji, Emoji linked with the new stat.
	 * @param Hashtag $hashtag, (optional) Hashtag linked with the new stat.
	 */
	public static function newDataPoint(StatisticsData $data, Emoji $emoji, Hashtag $hashtag = null) {
		$statistics = self::insertNew($data);

		$query = "INSERT INTO relation(idStat, idEmoji ,idHashtag) VALUES (:stats, :emoji, :hashtag)";

		$params = array(
			":stats" => $statistics->getId(),
			":emoji" => $emoji->getId(),
			":hashtag" => isset($hashtag)? $hashtag->getId() : null
		);

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute($params);
	}


	/**
	 * INSERT NEW STATISTICS
	 * @param StatisticsData $data
	 * @return Statistics instance, Instance of the added stats.
	 */
	private static function insertNew(StatisticsData $data) {
		require_once "Batch.class.php";

		// insert data and return the new entry
		$query = "
			INSERT INTO statistics(idBatch,  nbTweets,  avgRetweets,  avgFavorites,  avgResponses,  avgPopularity,  bestTweet)
			VALUES(:batch,  :nbTweets, :avgRetweets, :avgFavorites, :avgResponses, :avgPopularity, :best);
		";

		// bind values
		$params = array(
			":batch" => Batch::getActive()->getId(),
			":nbTweets" => $data->count(),
			":avgRetweets" => $data->avgRetweets(),
			":avgFavorites" => $data->avgFavorites(),
			":avgResponses" => $data->avgResponses(),
			":avgPopularity" => $data->avgPopularity(),
			":best" => $data->best()
		);

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute($params);

		return self::getLatest();
	}



	// disable constructor
	private function __construct() {}
}


/**
 * STATISTICS DATA INTERFACE
 * Objects must implement this interface to be inserted in the statistics table
 */
Interface StatisticsData {
	public function count();
	public function best();
	public function avgRetweets();
	public function avgFavorites();
	public function avgResponses();
	public function avgPopularity();
}