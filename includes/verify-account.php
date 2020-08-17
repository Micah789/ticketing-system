<?php if (Input::exists('post')) {

  if (!empty(Input::get('token'))) {
    $user = new User();

    $validate = new Validation();
    $validation = $validate->check($_GET, [
      'token'   => [
        'name' => 'Token',
        'required' => true,
      ]
    ]);

    if ($validation->passed()) {
      $current_user = $user->findByToken(Input::get('token'));

      if ($current_user->token === Input::get('token')) {
        $user->update([
          'token'   => null,
          'allowed' => 1
        ], $current_user->id);

        Session::flash('login', 'Your account is set up you can now login');
        Redirect::to('login.php');
      } else {
        throw new Exception("Cannot verify user");
        return false;
      }
    } else {
      foreach ($validation->errors() as $error) {
        printf('<span class="label alert">%s</span><br>', $error);
      }
    }
  } else {
    Redirect::to('register.php');
  }
}
