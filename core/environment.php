<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;

try {
  $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
  $dotenv->load();
} catch (InvalidPathException $e) {
  die($e->getMessage());
}

$GLOBALS['config'] = [
  'mysql' => array(
    'host' => $_SERVER["DB_HOST"],
    'username' => $_SERVER["DB_USERNAME"],
    'password' => $_SERVER["DB_PASSWORD"],
    'db' => $_SERVER["DB_NAME"]
  ),
  'remember' => array(
    'cookie_name' => 'hash',
    'cookie_expiry' => 604800
  ),
  'session' => array(
    'session_name' => 'user',
    'token_name' => 'token' 
  ),
  'email' => array(
    'sendgrid_api_key' => $_SERVER["SENDGRID_API_KEY"],
  )
];