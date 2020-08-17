<?php include 'partials/modules/header.php'; ?>
<?php require_once 'core/init.php';
require_once 'includes/verify-account.php'; ?>

<main>
  <div class="grid-container">

    <div class="spacer large"></div>
    <h3 class="text-center">Verify Account</h3>
    <div class="spacer"></div>

    <?php if (Session::exists('verify')) {
      printf('<div class="callout success">%s</div>', Session::flash('verify'));
    } ?>

    <form action="" method="post" autocomplete="on" class="grid-x form_login_reg">
      <div class="cell">
        <div class="input-group">
          <input class="input-group-field" type="text" name="activate_code" placeholder="Activation Code" value="<?= Input::get('token'); ?>" />
          <div class="input-group-button">
            <input type="submit" class="button" value="Submit">
          </div>
        </div>
      </div>
    </form>
  </div>
</main>

<?php include 'partials/modules/footer.php'; ?>