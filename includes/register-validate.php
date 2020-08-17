<?php
if(Input::exists()) {

  if (Token::check(Input::get('token'))) {

    if(Input::get('recaptcha_token')) {
      $url = "https://www.google.com/recaptcha/api/siteverify";
      $data = [
        'secret' => $_SERVER['RECAPTCHA_SECRET_KEY'],
        'response' => Input::get('recaptcha_token'),
        'remoteip' => $_SERVER['REMOTE_ADDR']
      ];

      $options = [
        'http' => [
          'header' => "Content-type: application/x-www-form-urlencoded\r\n",
          'method' => 'POST',
          'content' => http_build_query($data)
        ]
      ];

      $context = stream_context_create($options);
      $response = file_get_contents($url, false, $context);

      $result = json_decode($response, true);
    }

    $validate = new Validation();
    $validation = $validate->check($_POST, [
      'username'   => [
        'name' => 'Username',
        'required' => true,
        'min' => 2,
        'unique' => 'users'
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
      ],
      'user_type' => [
        'required' => true
      ],
      'password'   => [
        'name' => 'Password',
        'required' => true,
        'min' => 5,
      ],
      'cpassword'  => [
        'name' => 'Confirm Password',
        'required' => true,
        'matches' => 'password'
      ]
    ]);


    if($validation->passed() && $result['success'] == true && $result['score'] >= 0.5) {
      $user = new User();
      $email = new Email();

      $salt = Hash::salt(32);
      $token = bin2hex(random_bytes(50));

      try {

        $user->create([
          'username' => Input::get('username'),
          'nick_name' => Input::get('nick_name'),
          'email' => Input::get('email'),
          'telephone' => Input::get('telephone'),
          'password' => Hash::make(Input::get('password'), $salt),
          'salt' => $salt,
          'ip_address' => $_SERVER['REMOTE_ADDR'],
          'token' => $token,
          'allowed' => 0,
          'user_group_id' => Input::get('user_type')
        ]);

        $message = sprintf(
          "<html>
            <head>
              <title></title>
            </head>
            <body>
              <p>To activate your account, just confirm your email address by clicking on the 'Activate' link below</p>
              <p><a href='%s' target='_blank'>Activate</a></p>
            </body>
          </html>",
          "$_SERVER[HTTP_HOST]/ticketing/authentication.php?token=" . $token
        );

        $email->send('dev@mwb-agency.com', 'MWB', 'Welcome to MWB Ticking System', Input::get('email'), Input::get('nick_name'), $message);

        Session::flash('verify', 'You have been registered please check your email and enter in the authentication code');
        Redirect::to('authentication.php');

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
