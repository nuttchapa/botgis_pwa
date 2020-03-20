<?php
$access_token = '4D1qNJmm0xAaMRZCABdcz8+X/IH2eMKuKqD/1nSrWdPCQLiOZGlaD6tY4FUQ9spusBUSwAPR7D2W1hmnRHuPEk0X6a1YOKZfNYXFxAWvSKmH1ORbJ9vt6bW6HKAckdjKT6FYmQcJ9U95orRuBD1QTgdB04t89/1O/w1cDnyilFU=';

$url = 'https://api.line.me/v1/oauth/verify';

$headers = array('Authorization: Bearer ' . $access_token);

$ch = curl_init($url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
$result = curl_exec($ch);
curl_close($ch);

echo $result;
?>
