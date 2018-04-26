<?php
require_once 'PDO/MyPDO/MyPDO.emoji-tracker.include.php'; 

/**
 * HASHTAG CLASS
 * Represent the 'Hashtag‘ table of the database
 */

class Hashtag {

	//// PROPERTIES

	/**
	 * @var Integer $id, id of the Hashtag
	 */
	private $id = null;

	/**
	 * @var String $word, text corresponding to the hashtag (character '#' excluded)
	 */
	private $word = null;




	//// FACTORY

	/**
	 * CREATE FROM WORD
	 * @param String $word, text of the hashtag
	 * @return Hashtag instance, of the corresponding hashtag
	 * @throws Exception if hashtag not found.
	 */
	public static function createFromWord($word) {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM hashtag WHERE word = ?");
		$stmt->execute(array($word));
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Hashtag");
		if (($object = $stmt->fetch()) !== false)
			return $object;
		else
			throw new Exception("Hashtag \"$word\" is not referenced");
	}


	/**
	 * SUDO CREATE FROM WORD
	 * @param String $word, text of the hashtag
	 * @return Hashtag instance, of the corresponding hashtag
	 * If hashtag does not exists, a new row is added to the table.
	 *
	 * SHOULD ONLY BE CALLED BY TWITTER API SERVICE, USE createFromWord() INSTEAD.
	 */
	public static function sudoCreateFromWord($word) {
		$query = "
			INSERT INTO hashtag(word)
			SELECT * FROM (SELECT :word) AS tmp
			WHERE NOT EXISTS (
			    SELECT word FROM hashtag WHERE word = :word
			) LIMIT 1
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute(array(":word" => "$word"));

		return self::createFromWord($word);
	}



	//// BASIC GETTERS
	
	/**
	 * GET ID
	 * @return Integer $id, id of the Hashtag
	 */
	public function getId() { 
		return $this->id; 
	}

	/**
	 * GET WORD
	 * @return Integer $word, text of the Hashtag
	 */
	public function getWord() {
		return $this->word;
	}



	//// COMPLEX GETTERS

	/** 
	 * GET ALL HASHTAGS
	 * Grabs all the hashtags from the database
	 * @return array<Hashtag>, Array of instances of hashtag
	 */
	public static function getAll() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM hashtag");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Hashtag");

		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Failed to access to Hashtag table.");
	}


	/**
	 * GET ALL HASHTAGS FROM EMOJI
	 * returns a list of instances of Hashtags used with a given emoji
	 * @param Integer $idEmoji, id of the used emoji
	 * @return array<Hashtag>, array of instances of Hashtag
	 */
	public function getFromEmoji($idEmoji) {
		$query = "
			SELECT * FROM hashtag 
			WHERE id IN ( SELECT idHashtag FROM relation WHERE idEmoji = ? )
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->bindValue(1,$idEmoji);
		$stmt->execute();

		$stmt->setFetchMode(PDO::FETCH_CLASS, "Hashtag");
		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Emoji id:$idEmoji does not exists.");
	}



	// Disable constructor
	private function __construct() {}
}