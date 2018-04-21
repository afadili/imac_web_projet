<?php
require_once "TwitterAPIService.class.php";

/**
 * TWITTER AUTHENTIFICATION
 * ------------------------
 *
 * Api key : ???
 * Api secret key : ???
 * Api token : ???
 * Api secret token : ???
 */

$apikey = 'Z0arj4wYR0TlRIcpdy4tdAvIR';
$apisecret = '3px8MvckKG4TpaOuUysLK4j4mndgpvsfoXHxQeG3ZjfBwK3YDR';
$accesstoken = '325628241-MkdJ62Jy2wVC12yt8jsQQdzwTR8nqnTHB1mAmDIV';
$accesstokensecret = 'uaMveJiLbGvN4TKIBp1UdNVdyTYJS8Iabh0CMs3CZnLuY';

TwitterAPIService::setAPIAccessTokens($apikey, $apisecret, $accesstoken, $accesstokensecret);