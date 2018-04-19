<?php
require_once 'MyPDO/MyPDO.emoji-tracker.include.php'; 

/* -------------------------------------------------
 * EMOJI CLASS
 *  > get id, emoji character, name, shortname, 
 *    utf code, ascii representation
 * 	> get all
 *	> get from mood
 *	> get from hashtag
 * -------------------------------------------------
 */

class Emoji {

	/* --- Attributes --- */

	private $id = null;
	private $emoji = null;
	private $name = null;
	private $shortname = null;
	private $code = null;
	private $ascii = null;



	/* --- Constructor --- */
	
	// disable constructor
	function __construct() {}

	// create from emoji character
	public static function createFromChar($char) {
		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM emoji WHERE emoji = ?");
		$stmt->bindValue(1, $char);
		$stmt->execute();
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Emoji");
		if (($object = $stmt->fetch()) !== false)
			return $object;
		else
			throw new Exception("\$char is not a referenced emoji.");
	}

	// create from id
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


	/* --- Basic getters --- */

	// get $id
	public function getId() { 
		return $this->id; 
	}

	// get $emoji
	public function getEmoji() { 
		return $this->emoji; 
	}

	// get $name
	public function getName() { 
		return $this->name; 
	}

	// get $shortname
	public function getShortname() { 
		return $this->shortname; 
	}

	// get $code
	public function getCode() { 
		return $this->code; 
	}

	// get $ascii
	public function getAscii() { 
		return $this->ascii; 
	}



	/* --- Complex getters --- */

	/* GET ALL EMOJIS
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
			throw new Exception("Emoji table does'nt exist? Hmmm...");
	}




	/* GET MOOD
	 * @return instance of Mood
	 */

	public function getMood() {
		require_once "Mood.class.php";

		$stmt = MyPDO::getInstance()->prepare("SELECT * FROM mood WHERE id = ?");
		$stmt->execute(array($this->idMood));
		$stmt->setFetchMode(PDO::FETCH_CLASS, "Mood");
		
		if (($object = $stmt->fetch()) !== false)
			return $object;
		else
			throw new Exception("Emoji $this->shortname has no mood");
	}

	

	/* GET ALL EMOJIS FROM MOOD
	 * Select emojis from a given mood.
	 * @param int idMood
	 * @return array<Emoji>
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



	/* GET ALL EMOJIS FROM HASHTAG
	 * return a list of instances of Emoji used with a given Hashtag
	 * @params idHashtag, integer id of the used hashtag
	 * @return array<Emoji> list of instances of emoji
	 */
	public function getFromHashtag($idHashtag) {
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
}