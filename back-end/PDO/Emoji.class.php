<?php
require_once 'PDO/MyPDO/MyPDO.emoji-tracker.include.php'; 
require_once "PDO/Mood.class.php";

/**
 * EMOJI CLASS
 * Represent the 'emoji‘ table of the database
 */

class Emoji {

	//// ATTRIBUTES

	/**
	 * @var Integet $id, id of the emoji
	 */
	private $id = null;

	/**
	 * @var String $emoji, contains the emoji
	 * Emojis can be more than one character:
	 * For instance country flags are a pair of 'letters' for each ISO country codes.
	 */
	private $emoji = null;

	/**
	 * @var String $name, emoji description
	 */
	private $name = null;

	/**
	 * @var String $shortname, emoji short descrption (used in programs like Discord)
	 * Example: ':ok_hand:' -> 👌
	 */
	private $shortname = null;

	/**
	 * @var String $code, Unicode id (ex: U+1F320)
	 */
	private $code = null;

	/**
	 * @var String $ascii, ascii representation, for smileys (ex: ':-)')
	 * NULL if doesn't exists
	 */
	private $ascii = null;

	/**
	 * @var Integer $idMood, id of the corresponding mood, (defaults to NULL)
	 */
	private $idMood = null;




	//// FACTORIES

	/**
	 * Create Emoji From Char
	 * @param String $char, short string containing one emoji.
	 * @return Emoji instance
	 */
	public static function createFromChar($char) {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM emoji WHERE emoji = ?");
		$stmt->bindValue(1, $char);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Emoji");
		if (($object = $stmt->fetch()) !== false)
			return $object;
		else
			throw new Exception("\$char: '$char' is not a referenced emoji.");
	}


	
	/**
	 * Create Emoji From ID
	 * @param Integer $id, id of the emoji in db table
	 * @return Emoji instance
	 */
	public static function createFromId($id) {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM emoji WHERE id = ?");
		$stmt->bindValue(1, $id);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Emoji");
		if (($object = $stmt->fetch()) !== false)
			return $object;
		else
			throw new Exception("\$id does not match any referenced emoji in database.");
	}


	//// GETTERS

	/**
	 * Get id
	 * @return Integer, id of instance
	 */
	public function getId() { 
		return $this->id; 
	}

	/**
	 * Get emoji
	 * @return String, emoji of instance
	 */
	public function getEmoji() { 
		return $this->emoji; 
	}

	/**
	 * Get name
	 * @return String, name of instance
	 */
	public function getName() { 
		return $this->name; 
	}

	/**
	 * Get shortname
	 * @return String, shortname of instance
	 */
	public function getShortname() { 
		return $this->shortname; 
	}

	/**
	 * Get code
	 * @return String, unicode id of instance
	 */
	public function getCode() { 
		return $this->code; 
	}

	/**
	 * Get ascii
	 * @return String, ASCII representation of instance
	 */
	public function getAscii() { 
		return $this->ascii; 
	}



	//// COMPLEX GETTERS

	/**
	 * GET ALL EMOJIS
	 * Grabs all the emojis from the database
	 * @return array<Emoji>
	 */
	public static function getAll() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM emoji");
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Emoji");
		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Failed to access Emoji table.");
	}



	/**
	 * GET MOOD
	 * @return Mood Instance, Mood attached to the emoji
	 * @return Boolean false, if emoji as no mood
	 */
	public function getMood() {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM mood WHERE id = ?");
		$stmt->execute(array($this->idMood));
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Mood");
		return $stmt->fetch();
	}

	

	/**
	 * GET ALL EMOJIS FROM MOOD
	 * Select emojis matching a given mood.
	 * @param Integer $idMood, mood id.
	 * @return array<Emoji>, Array of instances of Emoji.
	 */
	public static function getFromMood($idMood) {
		$query = "
			SELECT * FROM emoji 
			WHERE idMood IN ( SELECT id FROM mood WHERE id = ? )
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute(array($idMood));

		$stmt->setFetchMode(PDO::FETCH_CLASS, "Emoji");
		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Mood id:$idMood does not exists.");
	}



	/**
	 * GET ALL EMOJIS FROM HASHTAG
	 * creates a list of instances of Emoji used with a given Hashtag
	 * @param Integer $idHashtag, id of the used hashtag
	 * @return array<Emoji>, Array of instances of Emoji
	 */
	public static function getFromHashtag($idHashtag) {
		$query = "
			SELECT * FROM emoji 
			WHERE id IN ( SELECT idEmoji FROM relation WHERE idHashtag = ? )
		";

		$stmt = MyPDO::getInstance()->prepare($query);
		$stmt->execute(array($idHashtag));

		$stmt->setFetchMode(PDO::FETCH_CLASS, "Hashtag");
		if (($object = $stmt->fetchAll()) !== false)
			return $object;
		else
			throw new Exception("Hashtag id:$idHashtag does not exists.");
	}


	
	// disable constructor
	private function __construct() {}
}