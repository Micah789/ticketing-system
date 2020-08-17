<?php if (Input::exists()) {
  if (Token::check(Input::get('token'))) {
    
    $validate = new Validation();
    $validation = $validate->check($_POST, [
      'username'   => [
        'name' => 'Username',
        'required' => true,
      ],
      'password'   => [
        'name' => 'Password',
        'required' => true,
      ]
    ]);

    if($validation->passed()) {
      
      $user = new User();

      $remember = !empty(Input::get('remember')) ? true : false;

      $login = $user->login(Input::get('username'), Input::get('password'), $remember);

      if($login) {
        Session::flash('dashboard', 'Welcome you have logged in!');
        Redirect::to('dashboard.php');    
      } else {
        Session::flash('login', 'You failed to login');
        Redirect::to('login.php');
      }

    } else {
      foreach ($validation->errors() as $error) {
        printf('<span class="label alert">%s</span><br>', $error);
      }
    }

  }
}