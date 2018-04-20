<?php
require_once 'TwitterAPIService.emoji-tracker.include.php';
require_once 'TwitterDataParser.class.php';

TwitterAPIService::start(new TwitterDataParser());