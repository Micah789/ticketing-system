<?php include 'partials/modules/header.php';
include 'core/init.php';

$user = new User();

if($user->isLoggedIn()) {
  include 'partials/components/menu.php';
}

if (!$user->isLoggedIn()) {
  Redirect::to('login.php');
}

require_once 'includes/update-password-validate.php'; ?>

<main>
  <div class="grid-container">
    <h3 class="text-center">Update Password</h3>
    <div class="spacer tiny"></div>
    <form class="grid-x form_login_reg" action="" method="post">
      <div class="cell">
        <label for="current_password">Current Password</label>
        <input type="password" name="current_password" value="<?= Input::get('current_password'); ?>">
      </div>

      <div class="cell">
        <label for="password_new">New Password</label>
        <input type="password" name="password_new" value="<?= Input::get('password_new'); ?>">
      </div>

      <div class="cell">
        <label for="password_new_again">Confirm New Password</label>
        <input type="password" name="password_new_again" value="<?= Input::get('password_new_again'); ?>">
      </div>

      <div class="spacer tiny"></div>

      <div class="cell">
        <input type="hidden" name="token" value="<?= Token::generate(); ?>">
        <button class="button primary" type="submit">Update your password</button>
      </div>
    </form>
  </div>
</main>


<?php include 'partials/modules/footer.php'; ?>