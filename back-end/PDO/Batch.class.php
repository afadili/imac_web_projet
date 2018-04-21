<?php
require_once '../PDO/MyPDO.emoji-tracker.include.php'; 

/* -------------------------------------------------
 * BATCH CLASS
 * -------------------------------------------------
 */

class Batch {
	/* --- Constants --- */
	// time in seconds before a batch is considered inactive
	const ACTIVE_BATCH_TTL = 120;

	/* --- Attributes --- */

	private $id = null;
	private $date = null;



	/* --- Basic Getters --- */
	// get id
	public function getId() { 
		return $this->id; 
	}

	// get date
	public function getDate() {
		return $this->date;
	}


	/* --- Complex Getters --- */

	/* GET ALL BATCHES
	 * Grabs all the batchs from the database
	 * @return array<Batch>
	 */

	public static function getAll() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM batch");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Batch");
		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Batch table does'nt exist? Hmmm...");
	}


	/* GET LATEST BATCH
	 * @return Batch instance
	 */

	public static function getLast() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM batch ORDER BY 'date' DESC LIMIT 1");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Batch");
		return $object = $stmt->fetch();
	}


	/* ---  Setter --- */
	/* WARNING: SETTERS SHOULD NEVER BE CALLED BY ANY FRONT-END REQUESTS */

	/* GET ACTIVE BATCH
	 * Grabs the latest batch and checks if it's still alive.
	 * If not, insert a new one.
	 * @returns Batch, latest batch in the database.
	 */
	public static function getActive() {
		$lastBatch = self::getLast();

		if ($lastBatch === false || time() - (new DateTime($lastBatch->date))->getTimestamp() > self::ACTIVE_BATCH_TTL) {
			$stmt = MyPDO::getInstance()->prepare("INSERT INTO batch(`date`) VALUES (CURRENT_TIMESTAMP)");
			$stmt->execute();
			$lastBatch = self::getLast();
		}

		return $lastBatch;
	}

	/* --- Constructor --- */
	
	// disable constructor
	private function __construct() {}


}