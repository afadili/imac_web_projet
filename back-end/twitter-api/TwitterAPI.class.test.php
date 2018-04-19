<?php
require_once 'TwitterAPI.emoji-tracker.include.php';

TwitterAPICall::connect();
$call = new TwitterAPICall();
echo $call->getTweetCount()."<br>";
var_dump($call->getEmojis());