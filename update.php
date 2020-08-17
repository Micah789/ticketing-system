<?php include 'partials/modules/header.php';
include 'core/init.php';

$user = new User();

if($user->isLoggedIn()) {
  include 'partials/components/menu.php';
}

if (!$user->isLoggedIn()) {
  Redirect::to('login.php');
}

require_once 'includes/update-validate.php'; ?>

<main>
  <div class="grid-container">
    <h3 class="text-center">Update Profile</h3>
    <div class="spacer tiny"></div>
    <form class="grid-x form_login_reg" action="" method="post">
      <div class="cell">
        <label for="username">Username</label>
        <input type="text" name="username" value="<?= escape($user->data()->username); ?>">
      </div>

      <div class="cell">
        <label for="nick_name">Nick Name</label>
        <input type="text" name="nick_name" value="<?= escape($user->data()->nick_name); ?>">
      </div>

      <div class="cell">
        <label for="email">Email Address</label>
        <input type="email" name="email" value="<?= escape($user->data()->email); ?>">
      </div>

      <div class="cell">
        <label for="telephone">Telephone</label>
        <input type="tel" name="telephone" value="<?= escape($user->data()->telephone); ?>">
      </div>

      <div class="spacer tiny"></div>

      <div class="cell">
        <input type="hidden" name="token" value="<?= Token::generate(); ?>">
        <button class="button primary" type="submit">Update your account</button>
      </div>
    </form>
  </div>
</main>


<?php include 'partials/modules/footer.php'; ?>