<?php
require_once 'PDO/MyPDO.emoji-tracker.include.php'; 

/* -------------------------------------------------
 * BATCH CLASS
 * -------------------------------------------------
 */

class Batch {
	/* --- Attributes --- */

	private $id = null;
	private $date = null;



	/* --- Constructor --- */
	
	// disable constructor
	function __construct() {}


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

	/* GET ALL BATCH
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
}