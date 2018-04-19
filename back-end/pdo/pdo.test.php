<?php

include "Emoji.class.php";
include "Hashtag.class.php";
include "Statistics.class.php";

foreach (Emoji::getAll() as $emoji) {
	echo 
	$emoji->getEmoji();
};