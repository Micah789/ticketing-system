<?php 

class Pagination 
{
  private $_db;
  public static $results_per_page = 4;
  private static $total_records;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }

  public static function setTotalPages($total_pages, $limit = null) 
  {
    self::$total_records = $total_pages;
  
    if(!is_null($limit)) {
      self::$results_per_page = $limit;
    }
    
    return;
  }

  public function getRows()
  {
    return $this->_db->query("SELECT * FROM tickets")->count();
  }

  public function getRowsByUserId($user_id)
  {
  return $this->_db->query("SELECT * FROM tickets WHERE user_id = {$user_id}")->count();
  }

  public static function rows()
  {
    return self::$results_per_page;
  }
  
  public static function getPaginationNumber()
  {
    return ceil(self::$total_records / self::$results_per_page);
  }

  public function numberOfPages($page_no)
  {
    
    return ($page_no - 1) * self::$results_per_page;
  }

  public function currentPage()
  {
    return isset($_GET['page']) ? (int)$_GET['page'] : 1;
  }

  public function activePage($page)
  {
    return ($page == $this->currentPage()) ? true : false;
  }

  public function previous()
  {
    return ($this->currentPage() > 1) ? $this->currentPage() - 1 : false;
  }
  
  public function next()
  {
    return ($this->getPaginationNumber() > $this->currentPage()) ? $this->currentPage() + 1 : false;
  }
}
