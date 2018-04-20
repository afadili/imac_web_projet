<?php
require 'TwitterAPIService.emoji-tracker.include.php';
require 'TwitterDataParser.class.php'

TwitterAPIService::start(new TwitterDataParser());