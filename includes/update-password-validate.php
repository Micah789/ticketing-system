<?php

if (Input::exists()) {
  if (Token::check(Input::get('token'))) {


    $validate = new Validation();

    $validation = $validate->check($_POST, [
      'current_password'   => [
        'name' => 'Password',
        'required' => true,
      ],
      'password_new'  => [
        'name' => 'New Password',
        'required' => true,
        'min' => 5
      ],
      'password_new_again'   => [
        'name' => 'Confirm New Password',
        'required' => true,
        'matches' => 'password_new'
      ],
    ]);


    if ($validation->passed()) {

      if (Hash::make(Input::get('current_password'), $user->data()->salt) !== $user->data()->password) {
        echo 'your current password is wrong';
      } else {
        $salt = Hash::salt(32);

        $user->update([
          'password' => Hash::make(Input::get('password_new'), $salt),
          'salt' => $salt
        ]);

        Session::flash('dashboard', 'Your details have been updated');
        Redirect::to('dashboard.php');
      }
    } else {
      foreach ($validation->errors() as $error) {
        printf('<span class="label alert">%s</span> <br>', $error);
      }
    }
  }
}
