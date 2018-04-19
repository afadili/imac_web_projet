<?php
require_once 'TwitterAPI.emoji-tracker.include.php';

TwitterAPICall::connect();
$call = new TwitterAPICall();
echo $call->getTweetCount();