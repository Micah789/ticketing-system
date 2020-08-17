<?php

class DB
{
  private static $_instance = null;
  private $conn;
  private $_query;
  private $_error = false;
  private $_results;
  private $_count;

  private function __construct()
  {
    try {
      $this->conn = new PDO('mysql:host=' . Config::get('mysql/host') . ';dbname=' . Config::get('mysql/db'), Config::get('mysql/username'), Config::get('mysql/password'));
    } catch (PDOException $e) {
      die($e->getMessage());
    }
  }

  public static function getInstance()
  {
    if (!isset(self::$_instance)) {
      self::$_instance = new DB();
    }
    return self::$_instance;
  }

  /**
  * @param string|mixed $sql the query
  * @param array        $params the parameters of the prepare statement
  * @return object      return results
  */
  public function query($sql, ?array $params = [])
  {
    $this->_error = false;
    if($this->_query = $this->conn->prepare($sql)) {

      $x = 1;

      if(count($params)) {
        foreach ($params as $param) {
          $this->_query->bindValue($x, $param);
          $x++;
        }
      }

      if ($this->_query->execute()) {
        $this->_results = $this->_query->fetchAll(PDO::FETCH_OBJ);
        $this->_count = $this->_query->rowCount();
      } else {
        $this->_error = true;
      }
    }

    return $this;
  }


  public function action($action, $table, $where = [])
  {
    if(count($where) === 3) {
      $operators = ['=', '>', '<', '>=', '<=', 'LIKE'];

      $field         = $where[0];
      $operator      = $where[1];
      $value         = $where[2];

      if(in_array($operator, $operators)) {
        $sql = "{$action} FROM {$table} WHERE {$field} {$operator} ?";
        
        if(!$this->query($sql, [$value])->error()) {
          return $this;
        }
      }
    }

    return false;
  }

  /**
  * Get data from the action method
  * @param string $table table name
  * @param array|mixed where statement
  */
  public function get($table, $where)
  {
    return $this->action('SELECT *', $table, $where);
  }

  public function delete($table, $where)
  {
    return $this->action('DELETE *', $table, $where);
  }

  /**
  * Insert into table with fields
  * @param string $table table name
  * @param array $fields all the fields
  * @return bool
  */
  public function insert($table, $fields = [])
  {
    if(count($fields)) {
      $keys = array_keys($fields);
      $values = '';
      $x = 1;

      foreach ($fields as $field) {
        $values .= '?';

        if($x < count($fields)) {
          $values .= ', ';
        }

        $x++;
      }

      $sql = "INSERT INTO {$table} (`" . implode('`, `', $keys) . "`) VALUES ({$values})";

      if (!$this->query($sql, $fields)->error()) {
        return true;
      }
    }

    return false;
  }

  public function update(?string $table, ?int $id, $fields = [])
  {
    $set = '';
    $x = 1;
    $keys = array_keys($fields);

    foreach ($fields as $name => $value) {
      $set .= "{$name} = ?";

      if($x < count($fields)) {
        $set .= ', ';
      }

      $x++;
    }

    $sql = "UPDATE {$table} SET {$set} WHERE id = {$id}";

    if (!$this->query($sql, $fields)->error()) {
      return true;
    }

    return false;
  }

  /**
  * Return the results of the query
  * @return mixed
  */
  public function results()
  {
    return $this->_results;
  }

  public function first()
  {
    return $this->results()[0];
  }

  /**
  * return a row count from query
  */
  public function count()
  {
    return $this->_count;
  }

  /**
  * checks if there an error
  */
  public function error()
  {
    return $this->_error;
  }
}
