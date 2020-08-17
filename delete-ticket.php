<?php require_once 'core/init.php';

if ($ticket_id = Input::get('id')) {
  
  $ticket = new Tickets();

  if($ticket->exist('id', $ticket_id)) {
    try {
      $ticket->delete($ticket_id);
      Session::flash('dashboard', 'Ticket is now deleted');
      Redirect::to('dashboard.php');
    } catch(Exception $e) {
      die($e->getMessage());
    }
  } else {
    return false;
  }
}