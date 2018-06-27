<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
require realpath(__DIR__.'/../').'/vendor/autoload.php';

use  Midhun\CleverReach\CleverReachClient as CleverReachClient;

$client = CleverReachClient::create(['client_id' => '188xxx', 'login' => 'xxxhun@revolve314.com', 'password' => 'xxx2hgPI' ]);
$client->authenticate();
$response = $client->addReceiver('502012',['email'=>"nazeer@revolve314.com","deactivated"=>"0"]);
echo '<pre>';
print_r($response);
echo '</pre>';
