<?php
require_once "PDO/MyPDO.emoji-tracker.include.php"

/* DATABASE UPDATER
 * 
 * WARNING: THE FOLLOWING CLASS AS THE POWER TO EDIT TABLES
 * NEVER *EVER* CALL ANY OF ITS METHODS WITH USER INPUT
 */

class DatabaseUpdater {

	private static $activeBatch = null;
	private static $stats = null;

	// privatize constructor
	private function __construct() {}


	public static function newDataPoint($emoji, $hashtag, array $data) {
		require_once "Emoji.class.php";
		require_once "Hashtag.class.php";

		// get hashtag
		$idHashtag = Hashtag::createFromName($hashtag);
		
		// add hashtag to database if doesn't exist
		if ($idHashtag === null) {
			$idHashtag = addHashtag($hashtag);
		}

		// get emoji
		$idEmoji = Emoji::createFromChar($emoji);

		// get stat
		$idStat = self::addStatistic($data);

		self::linkStatistic($idStat, $idEmoji, $idHashtag);
	}



	// create new batch
	public static function newBatch() {
		require_once "Batch.class.php";

		$query = "
			INSERT INTO batch('date') 
			VALUES (CURRENT_TIMESTAMP);
			
			SELECT id FROM batch 
			ORDER BY date DESC 
			LIMIT 1
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute();
		
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Batch");
		if (($object = $stmt->fetch()) !== false)
			self::$activeBatch  = $object;
		else
			throw new Exception("Failed to create new batch");

	}

	
	// add line to relation table
	private static function addRelation($idStat, $idEmoji, $idHashtag = null) {
		$query = "
			INSERT INTO relation(emoji,hashtag,statistics)
			VALUES (:emoji, :hashtag, :statistics)
		"

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute(array(
			":emoji" => $idEmoji, 
			":hashtag" => $idHashtag, 
			":statistics" => $idStat
		));
	}

	// add line to the statistics table
	private static function addStatistic($data) {
		require_once "Statistics.class.php";

		$query = "
			INSERT INTO statistics(
				idBatch, 
				nbTweets, 
				avgRetweets, 
				avgFavorites, 
				avgResponses, 
				avgPopularity, 
				bestTweet
			) VALUES (
				:batch,
				:count,
				:avgRetweets, 
				:avgFavorites, 
				:avgResponses, 
				:avgPopularity, 
				:best
			);

			SELECT id FROM batch 
			ORDER BY date DESC 
			LIMIT 1
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute($data);

		$stmt->setFetchMode(PDO::FETCH_CLASS, "Statistics");
		if (($object = $stmt->fetch()) !== false)
			return $object;
		else
			throw new Exception("Failed to create new batch");
	}


	/* add new hashtag
	 * @return hashtag id
	 */
	private static function addHashtag($word) {
		require_once "Hashtag.class.php";

		$query = "
			INSERT INTO hashtag(word) VALUES (?);
			SELECT * FROM hashtag ORDER BY id DESC LIMIT 1
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute();

		$stmt->setFetchMode(PDO::FETCH_CLASS, "Hashtag");
		if (($object = $stmt->fetch()) !== false)
			return $object;
		else
			throw new Exception("Failed to create new hashtag");
	}

}
	