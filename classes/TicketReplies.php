<?php

class TicketReplies
{
  private $_db;

  public function __construct()
  {
    $this->_db = DB::getInstance();
  }

  /**
   * Get ticket replies by ticket_id
   * @param int     $ticket_id
   * @return object results of the query
   */
  public function get($ticket_id)
  {
    if($ticket_id) {
      return $this->_db->get('ticket_replies', ['ticket_id', '=', $ticket_id])->results(); 
    } else {
      return false;
    }
  }

  /**
   * Adding reply data to database table
   * @param array $fields
   */
  public function create($fields = [])
  {
    if (!$this->_db->insert('ticket_replies', $fields)) {
      throw new Exception("Problem with saving your reply to this ticket.");
    }
  }

  public function updateTicketRepliesAmount($id, $amount)
  {
    $reply = $amount + 1;
    if(!$this->_db->update('tickets', $id, ['replies' => $reply])) {
      throw new Exception("Replies amount could not been update for this ticket");
    }
  }

  /**
   * delete ticket reply
   */
  public function delete(?int $ticket_id)
  {
    if (!$ticket_id) {
      return false;
    }
    
    if (!$this->_db->delete('ticket_replies', ['ticket_id', '=', $ticket_id])) {
      throw new Exception('There was a error deleting your reply');
    }
  }
}
