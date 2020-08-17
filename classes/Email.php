<?php
require 'vendor/autoload.php';

use Dotenv\Dotenv;
use Dotenv\Exception\InvalidPathException;
use SendGrid\Mail\Mail;

class Email {
  private $email;
  private $sendgrid;

  public function __construct() { 
    $this->email = new Mail();
    
    try {
      $dotenv = Dotenv::createImmutable(__DIR__ . '/../');
      $dotenv->load();
    } catch (InvalidPathException $e) {
      die($e->getMessage());
    }
    
    $this->sendgrid = new \SendGrid(Config::get('email/sendgrid_api_key'));
  }

  public function send($from_email, $from_name, $subject, $to_email, $to_name, $message)
  {
    $this->email->setFrom($from_email, $from_name);
    $this->email->setSubject($subject);
    $this->email->addTo($to_email, $to_name);
    $this->email->addContent('text/html',  $message);

    try {
      return $this->sendgrid->send($this->email);
    } catch (Exception $e) {
      echo $e->getMessage();
      return false;
    }
  }
}