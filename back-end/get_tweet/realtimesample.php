<?php
/*
*
*`curl –request GET
*  –url https://stream.twitter.com/1.1/statuses/sample.json –header ‘authorization: OAuth oauth_consumer_key=”Z0arj4wYR0TlRIcpdy4tdAvIR”,
*     oauth_nonce=”3px8MvckKG4TpaOuUysLK4j4mndgpvsfoXHxQeG3ZjfBwK3YDR”, oauth_signature=”GENERATED_SIGNATURE”, oauth_signature_method=”HMAC-SHA1”,
*     oauth_timestamp=”GENERATED_TIMESTAMP”, oauth_token=”325628241-MkdJ62Jy2wVC12yt8jsQQdzwTR8nqnTHB1mAmDIV”, oauth_version=”1.0”’`
*
*/

$url = 'https://stream.twitter.com/1.1/statuses/sample.json';
$method = 'GET';

$oauth_params = array(
    'oauth_consumer_key' => 'Z0arj4wYR0TlRIcpdy4tdAvIR',
    'oauth_nonce' => microtime(),
    'oauth_signature_method' => 'HMAC-SHA1',
    'oauth_timestamp' => time(),
    'oauth_token' =>'325628241-MkdJ62Jy2wVC12yt8jsQQdzwTR8nqnTHB1mAmDIV',
    'oauth_version' => '1.0a',
);

$base = $oauth_params;
$key = array('3px8MvckKG4TpaOuUysLK4j4mndgpvsfoXHxQeG3ZjfBwK3YDR','uaMveJiLbGvN4TKIBp1UdNVdyTYJS8Iabh0CMs3CZnLuY');
uksort($base, 'strnatcmp');

$oauth_params['oauth_signature'] = base64_encode(hash_hmac(
    'sha1',
    implode('&', array_map('rawurlencode', [
        'GET',
        $url,
        http_build_query($base, '', '&', PHP_QUERY_RFC3986)
    ])),
    implode('&', array_map('rawurlencode', $key)),
    true
));
foreach ($oauth_params as $name => $value) {
    $items[] = sprintf('%s="%s"', urlencode($name), urlencode($value));
}
$header = 'Authorization: OAuth ' . implode(', ', $items);
//var_dump($header);

$ch = curl_init();
curl_setopt_array($ch, array(
    CURLOPT_URL            => $url,
    CURLOPT_SSL_VERIFYPEER => false,
    CURLOPT_HTTPHEADER     => array($header),
    CURLOPT_ENCODING       => 'gzip',
    CURLOPT_TIMEOUT        => 0,
    CURLOPT_RETURNTRANSFER => false,
));


$response = curl_exec($ch);
curl_close($ch);
var_dump($response);




?>
