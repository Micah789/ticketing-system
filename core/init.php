<?php
session_start();

require_once 'error.php';
include_once 'environment.php';

spl_autoload_register(function($class){
  require_once 'classes/' . $class . '.php';
});

require_once 'functions/sanitize.php';
require_once 'functions/helpers.php';

if (Cookie::exists(Config::get('remember/cookie_name')) && !Session::exists(Config::get('session/session_name'))) {
  
  $hash = Cookie::get(Config::get('remember/cookie_name'));

  $hash_check = DB::getInstance()->get('users_session', ['hash', '=', $hash]);

  if($hash_check->count()) {
    $user = new User($hash_check->first()->user_id);
    $user->login();
  }
}