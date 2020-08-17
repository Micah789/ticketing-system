<?php if (Input::exists()) {

  $validate = new Validation();
  $validation = $validate->check($_POST, [
    'subject'   => [
      'name' => 'Subject',
      'required' => true,
      'min' => 10
    ],
    'content'   => [
      'name' => 'Content',
      'required' => true,
    ],
    'priority' => [
      'required' => true
    ]
  ]);

  if ($validation->passed()) {

    $ticket = new Tickets();
    $user = new User();

    try {

      $ticket_id = uniqid();
      
      $ticket->create([
        'ticket_id'   => $ticket_id,
        'subject'     => Input::get('subject'),
        'content'     => Input::get('content'),
        'priority'    => Input::get('priority'),
        'created'     => date('Y-m-d H:i:s'),
        'user_id'     => $user->data()->id,
        'status'      => 'open',
      ]);

      $subject = "New ticket has been created by {$user->data()->id} and its ticket no: {$ticket_id}";
      $message = sprintf(
        "<html>
          <head>
            <title></title>
          </head>
          <body>
            <p>New Ticket has been created</p>
            <p>Ticket No: %s</p>
            <table>
              <tr>
                <th style='text-align: left;'>Subject</th>
                <th></th>
                <th style='text-align: left;'>Created By %s</th>
                <th></th>
              </tr>
              <tr>
                <td>%s</td>
                <td></td>
                <td><a href='%s' target='_blank'>Click here to view ticket</a></td>
              </tr>
            </table>
          </body>
        </html>",
        $ticket_id,
        $user->data()->nick_name,
        Input::get('subject'),
        "$_SERVER[HTTP_HOST]/ticketing/view-ticket.php?id=".$ticket_id
      );

      $mail = new Email();
      $mail->send("dev@mwb-agency.com", "MWB", $subject, 'micah.k@mwb-agency.com', "Micah Kwaka", $message);

      Session::flash('dashboard', 'Your ticket has been saved!');
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
