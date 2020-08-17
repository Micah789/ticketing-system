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

      $ticket->update([
        'id'          => Input::get('id'),
        'ticket_id'   => Input::get('ticket_id'),
        'subject'     => Input::get('subject'),
        'content'     => Input::get('content'),
        'priority'    => Input::get('priority'),
        'updated'     => date('Y-m-d H:i:s'),
        'user_id'     => $user->data()->id,
        'status'      => Input::get('status'),
      ]);

      Session::flash('dashboard', 'Your ticket has been updated!');
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
