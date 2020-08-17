<?php
include 'partials/modules/header.php';
require_once 'core/init.php';
require_once 'includes/ticketing/create-validate.php';

$user = new User();
if ($user->isLoggedIn()) {
  include 'partials/components/menu.php';
} ?>

<main>
  <div class="grid-container">
    <h2 class="text-center">Create Ticket</h2>
    <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" class="grid-x form_login_reg">
      <div class="cell">
        <label for="subject">
          Subject
          <input type="text" name="subject" value="<?= Input::get('subject'); ?>" placeholder="Ticket subject" />
        </label>
      </div>

      <div class="cell">
        <label for="content">
          What is your issue?
          <textarea name="content" id="content" cols="30" rows="4" placeholder="content"><?= Input::get('content'); ?></textarea>
        </label>
      </div>

      <fieldset class="cell">
        <legend>Does this need to be sorted asap?</legend>
        <input type="radio" name="priority" value="yes"><label for="yes">Yes</label>
        <input type="radio" name="priority" value="no"><label for="no">No</label>
      </fieldset>

      <div class="spacer tiny"></div>

      <div class="cell">
        <input type="hidden" name="user_id" value="<?= escape($user->data()->id) ?>">  
        <button class="button success" type="submit">Save Ticket</button>
      </div>
    </form>
  </div>
</main>

<?php include 'partials/modules/footer.php'; ?>