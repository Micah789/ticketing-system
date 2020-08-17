<?php require_once 'core/init.php';

if ($ticket_id = Input::get('id')) {
  
  $ticket = new Tickets(); 
  $email = new Email();
  
  if($ticket->exist('id', $ticket_id)) {
    $user = new User();
    $ticker_owner = $user->find($ticket->exist('id', $ticket_id)->user_id);
    $to = $ticker_owner->email;
    $to_name = $ticker_owner->nick_name;

    switch($ticket->exist('id', $ticket_id)->status){
      case 'open':
        $ticket->changeStatus($ticket_id, 'close');
        Session::flash('dashboard', 'Ticket is now close');
        $subject = 'Ticket: '. $ticket->exist('id', $ticket_id)->ticket_id . " is now closed";

        $message = sprintf(
          "<html>
            <head>
              <title></title>
            </head>
            <body>
              <p>Ticket: %s is now closed</p>
            </body>
          </html>",
          $ticket->exist('id', $ticket_id)->ticket_id
        );
        $email->send("dev@mwb-agency.com", "MWB", $subject, $to, $to_name, $message);
      break;  
      
      case 'close':
        $ticket->changeStatus($ticket_id, 'open');
        Session::flash('dashboard', 'Ticket is now open again');
        $subject = 'Ticket: '. $ticket->exist('id', $ticket_id)->ticket_id . " is now open again";

        $message = sprintf(
          "<html>
            <head>
              <title></title>
            </head>
            <body>
              <p>Ticket: %s is now open again</p>
            </body>
          </html>",
          $ticket->exist('id', $ticket_id)->ticket_id
        );
        $email->send("dev@mwb-agency.com", "MWB", $subject, $to, $to_name, $message);
      break;  
    }

    Redirect::to('dashboard.php');
  } else {
    return false;
  }
}