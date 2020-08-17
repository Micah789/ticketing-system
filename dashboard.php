<?php
include 'partials/modules/header.php';
require_once 'core/init.php';

$user = new User();

if ($user->isLoggedIn()) {
  include 'partials/components/menu.php';
} ?>

<main>
  <div class="grid-container">

    <?php if ($user->isLoggedIn()) :
      if (Session::exists('dashboard')) {
        printf('<div class="callout success">%s</div>', Session::flash('dashboard'));
      } ?>

      <div class="grid-x">
        <div class="cell text-center">
          <h2>Dashboard</h2>
          <div class="spacer large"></div>
        </div>
      </div>

      <div class="grid-x grid-margin-x grid-margin-y">
        <div class="cell medium-6">
          <p><a href="create-ticket.php">Create Ticket</a></p>
        </div>

        <div class="cell medium-4 medium-offset-2 medium-text-right">
          <form action="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>">
            <input type="search" name="search" id="search-ticket" placeholder="Search Ticket" />
          </form>
        </div>
      </div>
      <p>View and manage tickets that may have responses from support team.</p>
      <?php $tickets = new Tickets();
      $permission = $user->hasPermission('admin'); ?>

      <table id="listTickets" class="table table-bordered table-striped">
        <thead>
          <tr>
            <th>S/N</th>
            <th>Ticket ID</th>
            <th>Subject</th>
            <th>Ticket Summary</th>
            <th>Created By</th>
            <th>Created</th>
            <th>Priority</th>
            <th>Status</th>
            <th></th>
            
            <?php if($permission) : ?>
              <th></th>
              <th></th>
            <?php endif; ?>
          </tr>
        </thead>
            
        <tbody>
          <?php
          $pagination = new Pagination();
          $page = $pagination->currentPage();

          if($user->data()->user_group_id == 1) {
            $all_tickets = $tickets->getAll($pagination->numberOfPages($page), $pagination::rows()); 
            $pagination::setTotalPages($pagination->getRows());
          } else {
            $all_tickets = $tickets->getAllByUserId($user->data()->id, $pagination->numberOfPages($page), $pagination::rows());
            $pagination::setTotalPages($pagination->getRowsByUserId($user->data()->id));
          }

          $number_of_pages = $pagination->getPaginationNumber();

          foreach ($all_tickets as $ticket) : ?>

            <tr>
              <td><?= $ticket->id; ?></td>
              <td><?= $ticket->ticket_id; ?></td>
              <td><?= $ticket->subject; ?></td>
              <td><?= show_max_char_length($ticket->content, 30); ?></td>
              <td><?= $user->find((int) $ticket->user_id)->nick_name; ?></td>
              <td>
                <?php
                $today = date_create(date('Y-m-d H:i:s'));
                $ticket_date = date_create($ticket->created);
                $diff = date_diff($today, $ticket_date);

                echo calc_date_diff($diff); ?>
              </td>
              <td>
                <?php
                switch ($ticket->priority) {
                  case 'no':
                    echo "<span class='label warning'>No</span>";
                  break;

                  case 'yes':
                    echo "<span class='label alert'>Yes</span>";
                  break;
                }
                ?>
              </td>
              <td>
                <?php
                switch ($ticket->status) {
                  case 'open':
                    echo "<span class='label success'>Open</span>";
                  break;

                  case 'closed':
                  case 'close':
                    echo "<span class='label alert'>Closed</span>";
                  break;
                }
                ?>
              </td>
              <td> <a class="button tiny primary" href='<?= "view-ticket.php?id={$ticket->ticket_id}" ?>'>View</a></td>

              <?php if($permission ) : ?>
                <td><a href='<?= "edit-ticket.php?id={$ticket->id}" ?>' class="button warning tiny">Edit</a></td>
                <td><a href='<?= "archive-ticket.php?id={$ticket->id}" ?>' class="button alert tiny">Archive</a></td>
              <?php endif; ?>
            </tr>

          <?php endforeach; ?>
        </tbody>

      </table>


      <?php if (!empty($number_of_pages)) { 
        include 'partials/components/pagination.php';
      } ?>

      <div class="spacer large"></div>

    <?php else : ?>

      <div class="callout large alert text-center">
        <h3>You need to <a href="login.php">log in</a> or <a href="register.php">register</a></h3>
      </div>

    <?php endif; ?>

  </div>
</main>

<?php include 'partials/modules/footer.php'; ?>