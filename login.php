<?php include 'partials/modules/header.php'; ?>
<?php require_once 'core/init.php'; ?>
<?php require_once 'includes/login-validate.php'; ?>

<main class="grid-container">
  <div class="spacer large"></div>
  <div class="grid-x">
    <div class="cell text-center">
      <h2>Login</h2>
      <div class="spacer tiny"></div>
    </div>
    <?php if (Session::exists('login')) {
      printf('<div class="callout alert">%s</div>', Session::flash('login'));
    } ?>
  </div>

  <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="on" class="grid-x form_login_reg">
    <div class="cell">
      <label for="username">Username <span>*</span>
        <input type="text" name="username" placeholder="username" value="<?= Input::get('username'); ?>" />
      </label>
    </div>

    <div class="cell">
      <label for="password">Password <span>*</span>
        <input type="password" name="password" placeholder="password" value="<?= Input::get('password'); ?>" />
      </label>
    </div>

    <div class="cell medium-6 align-self-middle">
      <label for="remember">
        <input type="checkbox" name="remember" value="on" id="remember"> Remember Me
      </label>
    </div>

    <div class="cell medium-6 medium-text-right align-self-middle">
      <a href="register.php">Need to sign up</a>
    </div>

    <div class="cell">
      <input type="hidden" name="token" value="<?= Token::generate(); ?>">
      <button type="submit" class="button primary">Login</button>
    </div>
  </form>
</main>
<?php include 'partials/modules/footer.php'; ?>