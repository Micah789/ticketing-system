<?php include 'partials/modules/header.php';
include 'core/init.php';
include 'includes/register-validate.php' ?>

<main class="grid-container">
  <div class="spacer large"></div>
  <div class="grid-x">
    <div class="cell text-center">
      <h2>Create Account</h2>
      <div class="spacer tiny"></div>
    </div>
  </div>

  <form id="register" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" autocomplete="on" class="grid-x form_login_reg">
    <div class="cell">
      <label for="username">Username <span>*</span>
        <input type="text" name="username" placeholder="username" value="<?= Input::get('username'); ?>" />
      </label>
    </div>

    <div class="cell">
      <label for="email">Email <span>*</span>
        <input type="email" name="email" placeholder="Email address" value="<?= Input::get('email'); ?>" />
      </label>
    </div>

    <div class="cell">
      <label for="nick_name">Full Name <span>*</span>
        <input type="text" name="nick_name" placeholder="Full name" value="<?= Input::get('nick_name'); ?>" />
      </label>
    </div>

    <div class="cell">
      <label for="telephone">Telephone
        <input type="tel" name="telephone" placeholder="Telephone" value="<?= Input::get('telephone'); ?>" />
      </label>
    </div>

    <fieldset class="cell">
      <legend>Are you a MWB employee or a MWB client?*</legend>
      <input type="radio" name="user_type" value="1" id="admin" <?= Input::get('user_type') == 1 ? 'checked' : false; ?>><label for="admin">Employee</label>
      <input type="radio" name="user_type" value="2" id="client" checked <?= Input::get('user_type') == 2 ? 'checked' : false; ?>><label for="client">Client</label>
    </fieldset>

    <div class="spacer tiny"></div>

    <div class="cell">
      <label for="password">Password <span>*</span>
        <input type="password" name="password" placeholder="password" value="<?= Input::get('password'); ?>" />
      </label>
    </div>

    <div class="cell">
      <label for="cpassword">Confirm Password <span>*</span>
        <input type="password" name="cpassword" placeholder="Confirm Password" value="<?= Input::get('cpassword'); ?>" />
      </label>
    </div>

    <div class="cell medium-6">
      <button type="submit" data-sitekey="<?= $_SERVER['RECAPTCHA_SITE_KEY']; ?>" data-callback='onSubmit' data-action='submit' id="registerBtn" class="button primary">Create Account</button>
    </div>

    <input type="hidden" name="recaptcha_token" id="token" />

    <input type="hidden" name="token" value="<?= Token::generate(); ?>" />

    <div class="cell medium-6 medium-text-right align-self-middle"><a href="login.php">Already have an account</a></div>
  </form>
</main>
<?php include 'partials/modules/footer.php'; ?>