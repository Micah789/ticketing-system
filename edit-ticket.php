<?php include 'partials/modules/header.php';
require_once 'core/init.php';
include 'includes/ticketing/update-validate.php';

$user = new User();

if ($user->isLoggedIn()) {
  include 'partials/components/menu.php';
}

if (!$user->isLoggedIn()) {
  Redirect::to('login.php');
}

if ($ticket_id = Input::get('id')) :
  $ticket = new Tickets();

  if ($ticket->exist('id', $ticket_id)) {
    $data = $ticket->find($ticket_id);
  } ?>

  <main>
    <div class="grid-container">
      <h3 class="text-center">Edit Ticket: <?= $data->ticket_id; ?></h3>
      <div class="spacer tiny"></div>
      <form class="grid-x form_login_reg" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
        <input type="hidden" name="id" value="<?= $data->id; ?>">
        <input type="hidden" name="ticket_id" value="<?= $data->ticket_id; ?>">
        <input type="hidden" name="status" value="<?= $data->status; ?>">

        <div class="cell">
          <label for="subject">
            Subject
            <input type="text" name="subject" value="<?= escape($data->subject); ?>">
          </label>
        </div>

        <div class="cell">
          <label for="content">
            What is your issue?
            <textarea name="content"><?= escape($data->content); ?></textarea>
          </label>
        </div>

        <fieldset class="cell">
          <legend>Does this need to be sorted asap?</legend>
          <input type="radio" name="priority" value="yes" <?= $data->priority == 'yes' ? 'checked' : false; ?>><label for="yes">Yes</label>
          <input type="radio" name="priority" value="no" <?= $data->priority == 'no' ? 'checked' : false; ?>><label for="no">No</label>
        </fieldset>

        <div class="spacer tiny"></div>
        <div class="cell">
          <button type="submit" class="button success alt">Update Ticket</button>
        </div>
      </form>
    </div>
  </main>

<?php endif;
include 'partials/modules/footer.php';
