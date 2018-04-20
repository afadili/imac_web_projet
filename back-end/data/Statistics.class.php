<?php
require_once '../PDO/MyPDO.emoji-tracker.include.php'; 

/* -------------------------------------------------
 * STATISTICS CLASS
 *  
 * -------------------------------------------------
 */

class Statistics {

	/* --- Attributes --- */

	private $id = null;
	private $nbTweets = null;
	private $avgRetweets = null;
	private $avgFavorites = null;
	private $avgResponses = null;
	private $avgPopularity = null;
	private $bestTweet = null;



	// create from id
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


	/* --- Basic getters --- */

	// get $id
	public function getId() { 
		return $this->id; 
	}

	
	// get nbTweets
	public function getNbTweets() {
		return $this->nbTweets;
	}
	
	// get avgRetweets
	public function getAvgRetweets() {
		return $this->avgRetweets;
	}
	
	// get avgFavorites
	public function getAvgFavorites() {
		return $this->avgFavorites;
	}
	
	// get avgResponses
	public function getAvgResponses() {
		return $this->avgResponses;
	}
	
	// get avgPopularity
	public function getAvgPopularity() {
		return $this->avgPopularity;
	}
	
	// get bestTweet
	public function getBestTweet() {
		return $this->bestTweet;
	}



	/* --- Complex getters --- */

	/* GET BATCH
	 * @return instance of batch
	 * batch gives info about the date of the sampling and groups stats together
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



	/* GET ALL STATS FROM BATCH
	 * Grabs all the statistics from one batch
	 * @param int idbatch id of the stats group
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


	/* GET ALL STATS FROM EMOJI
	 * Grabs all the statistics linked with an emoji
	 * Does not include statistics linked with an hashtag (see getFromEmojiHashtag)
	 * @param int idEmoji id of the emoji
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


	/* GET ALL STATS FROM EMOJI AND HASHTAG
	 * Grabs all the statistics linked with an emoji and an hashtag
	 * @param int idEmoji id of the emoji and int idHashtag id of the hashtag
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


	/* ---- SETTERS ---- */
	/* !!! WARNING: SETTERS AND CONSTRUCTORS SHOULD NEVER BE CALLED BY ANY FRONT-END REQUESTS !!! */
	public static function addStatistics(array $data) {
		require_once "Batch.class.php";

		// insert data and return the new entry
		$query = "
			INSERT INTO statistics(idBatch,  nbTweets,  avgRetweets,  avgFavorites,  avgResponses,  avgPopularity,  bestTweet)
			VALUES(:batch,  :nbTweets, :avgRetweets, :avgFavorites, :avgResponses, :avgPopularity, :best);
			SELECT * FROM statistics ORDER BY id DESC LIMIT 1;
		";

		// bind values
		// TODO : Interface getavgRetweet, fav etc...
		$params = array(
			":batch" => Batch::getActive()->getId(),
			":nbTweets" => $data["nbTweets"],
			":avgRetweets" => $data["avgRetweets"],
			":avgFavorites" => $data["avgFavorites"],
			":avgResponses" => $data["avgResponses"],
			":avgPopularity" => $data["avgPopularity"],
			":best" => $data["best"]
		);

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute($params);

		$stmt->setFetchMode(PDO::FETCH_CLASS, "Statistics");

		if (($obj = $stmt->fetch()) !== false) {
			return $obj;
		} else {
			throw new Exception("Failed to create stat");
		}
	}

	// disable constructor
	private function __construct() {}

}