<?php
require_once 'PDO/MyPDO//MyPDO.emoji-tracker.include.php'; 

/**
 * MOOD CLASS
 * Represent the 'Mood' table of the database
 */
class Mood {
	//// PROPERTIES

	/**
	 * @var Integer $id, id of the mood
	 */
	private $id = null;

	/**
	 * @var String $name, mood name
	 */
	private $name = null;



	//// BASIC GETTERS
	
	/**
	 * GET ID
	 * @return Integer, id of the current Mood
	 */
	public function getId() { 
		return $this->id; 
	}

	/**
	 * GET NAME
	 * @return String, name of the current Mood
	 */
	public function getName() {
		return $this->name;
	}



	//// COMPLEX GETTERS

	/** 
	 * GET ALL MOODS
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
			throw new Exception("Failed to access 'Mood' table.");
	}



	// disable constructor
	private function __construct() {}
}