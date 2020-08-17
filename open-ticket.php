<?php require_once 'core/init.php';

if ($ticket_id = Input::get('id')) {
  
  $ticket = new Tickets(); 
  $email = new Email();
  
  if($ticket->exist('id', $ticket_id)) {
    
    $user = new User();
    $ticker_owner = $user->find($ticket->exist('id', $ticket_id)->user_id);
    $to = $ticker_owner->email;
    $to_name = $ticker_owner->nick_name;

    $subject = 'Ticket: '. $ticket->exist('id', $ticket_id)->ticket_id . " is now open again";

    $message = sprintf(
      "<html>
        <head>
          <title></title>
        </head>
        <body>
          <h4>Ticket: %s is now open again</h4>
        </body>
      </html>",
      $ticket->exist('id', $ticket_id)->ticket_id
    );

    $email->send("dev@mwb-agency.com", "MWB", $subject, $to, $to_name, $message);

    $ticket->open($ticket_id);
    
    Session::flash('dashboard', 'Ticket is now open again');
    Redirect::to('dashboard.php');
  } else {
    return false;
  }
}