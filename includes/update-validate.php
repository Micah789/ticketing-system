<?php 

if (Input::exists()) {
  if(Token::check(Input::get('token'))) {
  

    $validate = new Validation();

    $validation = $validate->check($_POST, [
      'username'   => [
        'name' => 'Username',
        'required' => true,
        'min' => 2,
      ],
      'nick_name'   => [
        'name' => 'Nick Name',
        'required' => true,
      ],
      'email'   => [
        'name' => 'email',
        'required' => true,
        'min' => 2,
        'is_email' => true
      ]
    ]);

    if($validation->passed()) {

      try {
        
        $user->update([
          'username' => Input::get('username'),
          'nick_name' => Input::get('nick_name'),
          'telephone' => Input::get('telephone'),
          'email' => Input::get('email')
        ]);

        Session::flash('dashboard', 'Your details have been updated');
        Redirect::to('dashboard.php');

      } catch (Exception $e) {
        die($e->getMessage());
      }

    } else {
      foreach($validation->errors() as $error) {
        printf('<span class="label alert">%s</span> <br>', $error);
      }
    }
  }
}