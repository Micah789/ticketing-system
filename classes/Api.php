<?php

/**
 * Make api call to the teamworks api
 */
class Api
{
  private $token;
  private $request;
  private static $_instance = null;

  /**
   * Define constant if not already set.
   *
   * @param string      $name  Constant name.
   * @param string|bool $value Constant value.
   */
  private function define($name, $value)
  {
    if (!defined($name)) {
      define($name, $value);
    }
  }

  public function __construct()
  {
    $this->define('TW_API_URL', "https://makingwebsitesbetter.teamwork.com/");

    $this->request = curl_init();
    $this->token = $_SERVER['TEAMWORKS_API_TOKEN'];
  }

  public static function getInstance()
  {
    if (!isset(self::$_instance)) {
      self::$_instance = new Api();
    }
    return self::$_instance;
  }

  /**
   * @param string action of the get request
   * @param array the additional params for the http query
   */
  public function get(string $action, array $options = [])
  {

    $params = empty($options) ? '' : '?' . http_build_query($options);

    // set curl options
    curl_setopt($this->request, CURLOPT_URL, TW_API_URL . $action . '.json' . $params);
    curl_setopt($this->request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->request, CURLOPT_HTTPHEADER, [
      'Authorization: BASIC ' . base64_encode($this->token . ':xxx')
    ]);
  }

  /**
   * Post request into a existing project
   * @param int projectID the id of the project you want to add a task
   * @param array  request parameters
   */
  public function post(int $projId, array $body = [])
  {
    $arr = [
      'todo-item' => $body
    ];

    $json = json_encode($arr);

    curl_setopt($this->request, CURLOPT_URL, TW_API_URL . "projects/" . $projId . "/tasks.json");
    curl_setopt($this->request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->request, CURLOPT_POST, 1);
    curl_setopt($this->request, CURLOPT_POSTFIELDS, $json);
    curl_setopt($this->request, CURLOPT_HTTPHEADER, [
      'Authorization: BASIC ' . base64_encode($this->token . ':xxx'),
      "Content-type: application/json"
    ]);
  }

  /**
   * mark a task complete
   * @param int task it
   */
  public function complete(int $taskId)
  {
    if (!$taskId) {
      return $this;
    }

    curl_setopt($this->request, CURLOPT_URL, TW_API_URL . "tasks/" . $taskId . "/complete.json");
    curl_setopt($this->request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->request, CURLOPT_CUSTOMREQUEST, "PUT");
    curl_setopt($this->request, CURLOPT_HTTPHEADER, [
      'Authorization: BASIC ' . base64_encode($this->token . ':xxx')
    ]);
  }

  /**
   * Delete a task using task it
   * @param int task id
   */
  public function delete(int $taskId)
  {
    if (!$taskId) {
      return $this;
    }

    curl_setopt($this->request, CURLOPT_URL, TW_API_URL . "tasks/" . $taskId . ".json");
    curl_setopt($this->request, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($this->request, CURLOPT_CUSTOMREQUEST, "DELETE");
    curl_setopt($this->request, CURLOPT_HTTPHEADER, [
      'Authorization: BASIC ' . base64_encode($this->token . ':xxx')
    ]);
  }

  /**
   * Execute the api request
   * @param bool $echo if you want to see the results this is mainly for a get request
   */
  public function exec()
  {

    $result = curl_exec($this->request);

    if ($result) {
      return $this->result($result);
    } else {
      echo curl_error($this->request);
    }

    curl_close($this->request);
  }

  /**
   * @return array|string Json as assoiciated array
   */
  private function result($result)
  {
    return json_decode($result, true);
  }

  /**
   * Check if the response code is 300 or 200
   * @return bool | string
   */
  public function status()
  {
    $http_status = curl_getinfo($this->request, CURLINFO_HTTP_CODE);

    // Checking the response code and return the correct message or data
    if ($http_status === 302 || $http_status === 200) {
      return true;
    } else if ($http_status === 500 || $http_status === 404 || $http_status === 422 || $http_status === 400) {
      error_log("Request Failed");
      echo "{$http_status}: Request Failed";
    } else if ($http_status === 201) {
      echo "Request successful task created";
    } else if ($http_status === 401 || 403) {
      error_log("You are Unauthorized");
      echo "{$http_status}: You are Unauthorized";
    }
  }
}
