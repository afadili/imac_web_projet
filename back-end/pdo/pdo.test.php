<?php

include "Model/Emoji.class.php";
include "Model/Hashtag.class.php";
include "Model/Statistics.class.php";

foreach (Emoji::getAll() as $emoji) {
	echo 
	$emoji->getEmoji();
};