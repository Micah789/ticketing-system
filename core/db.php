<?php
$GLOBALS['config'] = [
  'mysql' => array(
    'host' => '127.0.0.1',
    'username' => 'root',
    'password' => '',
    'db' => 'ticketing_system'
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
    'sendgrid_api_key' => 'xxxx'
  )
];
