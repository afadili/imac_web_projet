<?php
require_once 'Twitter-API-Service/TwitterAPIService.emoji-tracker.include.php';
require_once 'Data-Parser/TwitterDataParser.class.php';

TwitterAPIService::start(new TwitterDataParser());