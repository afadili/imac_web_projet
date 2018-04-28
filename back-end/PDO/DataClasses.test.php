<?php
require_once "Batch.class.php";
require_once "Statistics.class.php";
require_once "Mood.class.php";
require_once "Emoji.class.php";
require_once "Hashtag.class.php";

// add "hello" and "world" to database
var_dump(Hashtag::sudoCreateFromWord("hello"));
var_dump(Hashtag::sudoCreateFromWord("world"));

// the first an second vardump should have the same id
var_dump(Hashtag::sudoCreateFromWord("hello"));