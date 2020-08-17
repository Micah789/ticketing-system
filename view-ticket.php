<?php include 'partials/modules/header.php';
require_once 'core/init.php';

$user = new User();

if ($user->isLoggedIn()) {
  include 'partials/components/menu.php';
}

if (!$user->isLoggedIn()) {
  Redirect::to('login.php');
}

if ($ticket_id = Input::get('id')) :
  $ticket = new Tickets();

  if ($ticket->exist('ticket_id', $ticket_id)) {
    $data = $ticket->find($ticket_id);
  }

  if (Session::exists('ticket_reply')) {
    printf('<div class="callout success">%s</div>', Session::flash('ticket_reply'));
  } ?>

  <main>
    <div class="grid-container">

      <div class="grid-x">
        <div class="cell medium-6">
          <h3>Ticket no: <?= $data->ticket_id; ?></h3>
        </div>

        <div class="cell medium-auto">
          <a href=""></a>
        </div>
        <div class="spacer tiny"></div>
      </div>


      <div class="card">
        <div class="card-divider grid-x grid-margin-x align-middle">
          <div class="cell shrink">

            <?php switch ($data->status) {
              case 'open':
                echo "<span class='label success' style='align-self: center;'>Open</span>";
                break;

              case 'closed':
              case 'close':
                echo "<span class='label alert' style='align-self: center;'>Closed</span>";
                break;
            } ?>
          </div>

          <div class="cell shrink">
            <h5 style="margin-bottom: 0;"><?= escape($data->subject) ?></h5>
          </div>

          <?php if ($user->hasPermission('admin')) : ?>
            <div class="cell shrink">
              <?php if ($data->status == 'close') {
                echo "<a class='button warning' href='ticket-status.php?id=$data->id'>Reopen ticket</a>";
              } else {
                echo "<a class='button alert' href='ticket-status.php?id=$data->id'>Close ticket</a>";
              } ?>
            </div>
          <?php endif; ?>
        </div>

        <div class="card-section">
          <p><?= $data->content; ?></p>
        </div>

        <div class="card-divider grid-x grid-margin-x">
          <div class="cell small-4">
            <?php
            $today = date_create(date('Y-m-d H:i:s'));
            $ticket_date = date_create($data->created);
            $diff = date_diff($today, $ticket_date);

            echo calc_date_diff($diff);
            ?>
          </div>

          <div class="cell small-4">
            <?= $user->find((int) $data->user_id)->nick_name; ?>
          </div>
          <div class="cell small-4">
            <?php switch ($data->priority) {
              case 'no':
                echo "<span class='label warning'>No it is not priority</span>";
                break;

              case 'yes':
                echo "<span class='label alert'>Yes be resolved quickly</span>";
                break;
            } ?>
          </div>
        </div>

      </div>
      <div class="spacer tiny"></div>

      <?php include 'partials/modules/replies.php';
      include 'includes/ticketing/reply-validate.php'; ?>

      <!-- Start of ticket replies form -->
      <form action="" method="post">
        <input type="hidden" name="user_id" value="<?= escape($user->data()->id) ?>">
        <input type="hidden" name="main_ticket_id" value="<?= $data->id; ?>">
        <input type="hidden" name="ticket_replies" value="<?= $data->replies; ?>">
        <input type="hidden" name="ticket_owner_id" value="<?= $user->find((int) $data->user_id)->id; ?>">

        <fieldset class="grid-x">
          <legend class="h5">Reply to this ticket?</legend>
          <div class="cell">
            <textarea name="comment" rows="8" placeholder="Enter your reply..."></textarea>
          </div>

          <div class="cell">
            <button type="submit" class="button success">Reply</button>
          </div>
        </fieldset>
      </form>

      <!-- End of ticket replies form -->
    </div>
  </main>


<?php endif;
include 'partials/modules/footer.php';
