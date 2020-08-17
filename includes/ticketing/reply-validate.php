<?php if (Input::exists()) {

  $validate = new Validation();
  $validation = $validate->check($_POST, [
    'comment'   => [
      'name' => 'Comment',
      'required' => true,
      'min' => 2
    ]
  ]);

  if ($validation->passed()) {

    $ticket_reply = new TicketReplies();
    $user = new User();

    try {

      $ticket_reply->create([
        'ticket_id' => Input::get('main_ticket_id'),
        'user_id'   => Input::get('user_id'),
        'comment'   => Input::get('comment'),
        'date'      => date('Y-m-d H:i:s')
      ]);

      $ticket_reply->updateTicketRepliesAmount(Input::get('main_ticket_id'), Input::get('ticket_replies'));

      $user_data = $user->find(Input::get('user_id'));

      // Check if user is admin or client
      if ($user_data->user_group_id != 1) {
        $to = "micah.k@mwb-agency.com";
        $to_name = "micah kwaka";
      } else {
        $ticket_owner = $user->find(Input::get('ticket_owner_id'));
        $to = $ticket_owner->email;
        $to_name = $ticket_owner->nick_name;
      }
      
      // Email prep
      $subject = 'Ticket: '. Input::get('id'). " has a reply from {$to_name}";
      $message = sprintf(
        "<html>
          <head>
            <title></title>
          </head>
          <body>
            <p>Ticket: %s has a reply from %s</p>
            <p><a href='%s' target='_blank'>Click here</a> to view the reply</p>
          </body>
        </html>",
        Input::get('id'),
        $to_name,
        "$_SERVER[HTTP_HOST]/ticketing/view-ticket.php?id=" . Input::get('id')
      );
      
      $mail = new Email();
      $mail->send($user_data->email, $user_data->nick_name, $subject, $to, $to_name, $message);

      
      Session::flash('dashboard', 'Your reply to this ticket no:' . Input::get('id') . ' has been saved!');
      Redirect::to('dashboard.php');
    } catch (Exception $e) {
      die($e->getMessage());
    }
  } else {
    foreach ($validation->errors() as $error) {
      printf('<span class="label alert">%s</span><br>', $error);
    }
  }
}
