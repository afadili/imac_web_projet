<?php
require_once 'PDO/MyPDO//MyPDO.emoji-tracker.include.php'; 

/* -------------------------------------------------
 * Mood CLASS
 *	> get id, name
 *	> get all
 *	> get from emoji
 * -------------------------------------------------
 */

class Mood {
	/* --- Attributes --- */

	private $id = null;
	private $name = null;



	/* --- Basic Getters --- */
	// get id
	public function getId() { 
		return $this->id; 
	}

	// get name
	public function getName() {
		return $this->name;
	}



	/* --- Complex Getters --- */

	/* GET ALL MOODS
	 * Grabs all the moods from the database
	 * @return array<Mood>
	 */

	public static function getAll() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM mood");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Mood");
		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Mood table does'nt exist? Hmmm...");
	}



	/* --- Constructor --- */
	
	// disable constructor
	private function __construct() {}
}