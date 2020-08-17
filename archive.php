<?php
include 'partials/modules/header.php';
require_once 'core/init.php';

$user = new User();

if ($user->isLoggedIn() && $user->hasPermission('admin')) {
  include 'partials/components/menu.php';
} else {
  Redirect::to('login.php');
} ?>

<main>
  <div class="grid-container">
    <h2 class="text-center">Archive Tickets</h2>

    <div class="spacer medium"></div>

    <div class="grid-x">
      <div class="cell">
        <p>Reopen or permanlty delete tickets</p>
        <?php $tickets = new Tickets(); ?>
      </div>
    </div>

    <table id="listTickets" class="table table-bordered table-striped">
      <thead>
        <tr>
          <th>S/N</th>
          <th>Ticket ID</th>
          <th>Subject</th>
          <th>Ticket Summary</th>
          <th></th>
          <th></th>
        </tr>
      </thead>

      <tbody>
        <?php foreach ($tickets->getArchiveTickets() as $ticket) : ?>

          <tr>
            <td><?= $ticket->id; ?></td>
            <td><?= $ticket->ticket_id; ?></td>
            <td><?= $ticket->subject; ?></td>
            <td><?= show_max_char_length($ticket->content, 120); ?></td>
            <td><a href='<?= "open-ticket.php?id={$ticket->id}" ?>' class="button success tiny">Open Ticket</a></td>
            <td><a href='<?= "delete-ticket.php?id={$ticket->id}" ?>' class="button alert tiny">Delete</a></td>
          </tr>

        <?php endforeach; ?>
      </tbody>

    </table>
  </div>
</main>


<?php include 'partials/modules/footer.php'; ?>