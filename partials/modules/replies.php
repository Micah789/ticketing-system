<?php $replies = new TicketReplies();
foreach ($replies->get($data->id) as $reply) : ?>

  <div class="card">
    <div class="card-divider">
      <?= $user->find((int) $reply->user_id)->nick_name; ?> &nbsp;
      <?php
      $today = date_create(date('Y-m-d H:i:s'));
      $ticket_date = date_create($reply->date);
      $diff = date_diff($today, $ticket_date);

      echo calc_date_diff($diff); ?>
    </div>

    <div class="card-section">
      <p><?= $reply->comment; ?></p>
    </div>
  </div>

<?php endforeach; ?>

<div class="spacer large"></div>