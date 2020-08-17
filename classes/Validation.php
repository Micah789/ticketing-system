<?php

/**
* Validate fields
*/
class Validation
{
  private $_errors = [];
  private $_passed = false;
  private $_db = null;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }

  public function check($source, $items = [])
  {
    foreach ($items as $item => $rules) {

      foreach ($rules as $rule => $rule_value) {

        $value = $this->cleanValue($source[$item]);
        $item = escape($item);

        if($rule === 'required' && empty($value)) {
          $this->addError("{$item} is required");
        } else if(!empty($value)) {
          switch ($rule) {
            case 'min':

              if(strlen($value) < $rule_value) {
                $this->addError("{$item} must be minimum of {$rule_value} characters");
              }

            break;

            case 'max':

              if(strlen($value) >= $rule_value) {
                $this->addError("{$item} must be less of {$rule_value} characters");
              }

            break;

            case 'matches':

              if($value != $source[$rule_value]) {
                $this->addError("{$rule_value} must match {$item}");
              }

            break;

            case 'unique':

              $check = $this->_db->get($rule_value, [$item, "=", $value]);

              if($check->count()) {
                $this->addError("{$item} already exists");
              }

            break;

            case 'is_email':

              $email = filter_var($value, FILTER_SANITIZE_EMAIL);

              // Validate e-mail
              if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) {
                $this->addError("{$item} is not a valid email address");
              }

            break;
          }
        }
      }

    }

    if(empty($this->_errors)) {
      $this->_passed = true;
    }

    return $this;
  }

  private function cleanValue($value)
  {
    $value = trim($value);
    $value = stripslashes($value);
    $value = htmlspecialchars($value);
    return $value;
  }

  public function addError($error)
  {
    $this->_errors[] = $error;
  }

  public function errors()
  {
    return $this->_errors;
  }

  public function passed()
  {
    return $this->_passed;
  }
}
