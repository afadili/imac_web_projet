<?php
require_once "PDO/MyPDO.emoji-tracker.include.php"

/* DATABASE UPDATER
 * 
 * WARNING: THE FOLLOWING CLASS AS THE POWER TO EDIT TABLES
 * NEVER *EVER* CALL ANY OF ITS METHODS WITH USER INPUT
 */

class DatabaseUpdater {

	private static $activeBatch = null;

	// privatize constructor
	private function __construct() {}


	// create new batch
	private static newBatch() {
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

	/* add new hashtag
	 * @return hashtag id
	 */
	private static addHashtag($word) {
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