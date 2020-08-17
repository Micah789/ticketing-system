<?php require_once 'core/init.php';

if ($ticket_id = Input::get('id')) {
  
  $ticket = new Tickets(); 
  $email = new Email();
  
  if($ticket->exist('id', $ticket_id)) {

    $user = new User();
    $ticker_owner = $user->find($ticket->exist('id', $ticket_id)->user_id);
    $to = $ticker_owner->email;
    $to_name = $ticker_owner->nick_name;

    $subject = 'Ticket: '. $ticket->exist('id', $ticket_id)->ticket_id . " is now archived";

    $message = sprintf(
      "<html>
        <head>
          <title></title>
        </head>
        <body>
          <h4>Ticket: %s is now archived</h4>
        </body>
      </html>",
      $ticket->exist('id', $ticket_id)->ticket_id
    );

    $email->send("dev@mwb-agency.com", "MWB", $subject, $to, $to_name, $message);

    $ticket->close($ticket_id);
    
    Session::flash('dashboard', 'Ticket is now close');
    Redirect::to('dashboard.php');
  } else {
    return false;
  }
}