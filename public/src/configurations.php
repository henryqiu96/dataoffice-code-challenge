<?php
$endpoint = 'http://127.0.0.1:9090/';
$creadentials = [
    // if they were actual AWS credentials, they must not be hardcoded here
    'key' => 'root',
    'secret' => '1234Abcd'
];
$region = 'us-east-1'; // randomly chosen, apparently any arbitrary value is valid
$bucketName = 'dataoffice-code-challenge';
$keyName = 'AllPrintings.json';
?>