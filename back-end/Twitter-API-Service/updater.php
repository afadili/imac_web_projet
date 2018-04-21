<?php
require_once 'Twitter-API-Service/TwitterAPIService.emoji-tracker.include.php';
require_once 'Data-Parser/TwitterDataParser.class.php';

/* START SERVICE
 * Launch the twitter API Service
 * Updates the database with new tweet statistics at a set interval.
 */

TwitterAPIService::start(new TwitterDataParser());