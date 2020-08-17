<?php

class Tickets
{
  private $_db;
  private $_data;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }

  /**
   * Create User
   */
  public function create($fields = [])
  {
    if (!$this->_db->insert('tickets', $fields)) {
      throw new Exception("Problem with saving ticket.");
    }
  }

  public function find($ticket_id = null)
  {
    if($ticket_id) {
      $field = (is_numeric($ticket_id)) ? 'id' : 'ticket_id';

      $data = $this->_db->get('tickets', [$field, '=', $ticket_id]);
      
      if($data->count()) {
        $this->_data = $data->first();
        return $this->_data;
      }
    }

    return false;
  }

  /**
   * Get all tickets
   * @return mixed all tickets
   */
  public function getAll($limit = null, $results_per_page = null)
  {
    if(!$limit && !$results_per_page) {
      return $this->_db->query("SELECT * FROM tickets WHERE closed = 0")->results();
    } else {
      return $this->_db->query("SELECT * FROM tickets WHERE closed = 0 LIMIT  {$limit} , {$results_per_page}")->results();
    }
  }

  /**
   * Get all tickets based on your user_id
   */
  public function getAllByUserId($user_id, $limit = null, $results_per_page = null)
  {
    if(!$limit && !$results_per_page) {
      return $this->_db->query("SELECT * FROM tickets WHERE closed = 0 AND user_id = {$user_id}")->results();
    } else {
      return $this->_db->query("SELECT * FROM tickets WHERE closed = 0 AND user_id = {$user_id} LIMIT {$limit} , {$results_per_page}")->results();
    }
  }

  public function exist($field, $value)
  {
    return $this->_db->get('tickets', [$field, '=', $value])->first();
  }

  public function update($fields = [])
  {
    if($fields) {
      if(!$this->_db->update('tickets', $fields['id'], $fields)) {
        throw new Exception("Ticket could not be updated");
      }
    }
  }

  public function changeStatus($id, $status)
  {
    if($id) {
      if($status == 'close' || $status == 'open') {
        if(!$this->_db->update('tickets', $id, ['status' => $status])) {
          throw new Exception("Ticket status could not be updated");
        }
      }
    }
  }

  public function open(?int $id)
  {
    if (!$id) {
      return false;
    }
    
    if (!$this->_db->update('tickets', $id, ['closed' => 0, 'status' => 'open'])) {
      throw new Exception('There was a error open your ticket');
    }
  }

  public function close(?int $id)
  {
    if (!$id) {
      return false;
    }
    
    if (!$this->_db->update('tickets', $id, ['closed' => 1, 'status' => 'close'])) {
      throw new Exception('There was a error closing your ticket');
    }
  }

  public function delete($id)
  {
    if(!$id) {
      return false;
    }

    if(!$this->_db->query("DELETE FROM tickets WHERE id = {$id}")) {
      throw new Exception('There was a error deleting your ticket');
    }
  }

  public function getArchiveTickets($limit = null, $results_per_page = null)
  {
    if(!$limit && !$results_per_page) {
      return $this->_db->query("SELECT * FROM tickets WHERE closed = 1")->results();
    } else {
      return $this->_db->query("SELECT * FROM tickets WHERE closed = 1 LIMIT  {$limit} , {$results_per_page}")->results();
    }
  }
}
