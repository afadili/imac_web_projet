<?php
require_once 'PDO/MyPDO/MyPDO.emoji-tracker.include.php'; 

/**
 * BATCH CLASS
 * Represent the 'Batch' table of the database
 */

class Batch {
	//// CONSTANTS

	/**
	 * @const Integer ACTIVE_BATCH_TTL
	 * time in seconds before a batch is considered inactive
	 * (TTL: Time To Live)
	 * ~> See Batch::getActive()
	 */
	const ACTIVE_BATCH_TTL = 120;



	//// PROPERTIES

	/**
	 * @var Integer $id, id of the Batch
	 */
	private $id = null;

	/**
	 * @var Date $date, creation date of the batch
	 */
	private $date = null;



	//// BASIC GETTERS
	
	/**
	 * GET ID
	 * @return Integer, id of the current batch
	 */
	public function getId() { 
		return $this->id; 
	}

	/**
	 * GET DATE
	 * @return Date, date of creation of the batch
	 */
	public function getDate() {
		return $this->date;
	}


	/**
	 * IS ACTIVE
	 * @return Boolean, is the current batch active?
	 */
	public function isActive() {
		$thisTimestamp = (new DateTime($this->date))->getTimestamp();
		return time() - $thisTimestamp > self::ACTIVE_BATCH_TTL;
	}


	
	//// COMPLEX GETTERS

	/**
	 * GET ALL BATCHES
	 * Grabs all batches from the database
	 * @return array<Batch>, array of instances of Batch
	 */
	public static function getAll() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM batch");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Batch");

		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Failed to access the 'batch' table");
	}


	/** 
	 * GET LATEST BATCH
	 * Grabs the most recent batch from the database
	 * @return Batch instance
	 * @return Boolean false, if batch table empty
	 */
	public static function getLast() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM batch ORDER BY 'date' DESC LIMIT 1");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Batch");
		return $object = $stmt->fetch();
	}


	/**
	 * GET ACTIVE BATCH
	 * Grabs the latest batch and checks if it's still alive.
	 * If there are no active batch, INSERT a new one
	 * SHOULD ONLY BE CALLED BY TWITTER API SERVICE, USE getLast() OR isActive() INSTEAD.
	 * @return Batch instance, latest active batch in the database.
	 */
	public static function getActive() {
		$lastBatch = self::getLast();

		if ($lastBatch === false || $lastBatch->isActive() === false) {
			$stmt = MyPDO::getInstance()->prepare("INSERT INTO batch(`date`) VALUES (CURRENT_TIMESTAMP)");
			$stmt->execute();
			$lastBatch = self::getLast();
		}

		return $lastBatch;
	}


	
	// disable constructor
	private function __construct() {}
}