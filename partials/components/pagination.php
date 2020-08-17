<?php
if ($user->data()->user_group_id == 1) {
  $total_pages = count($tickets->getAll());
} else {
  $total_pages = count($tickets->getAllByUserId($user->data()->id));
}

$pagination::setTotalPages($total_pages, $pagination::rows());
$max_pages = $pagination->getPaginationNumber(); ?>

<nav aria-label="Pagination">
  <ul class="pagination">

    <?php if ($pagination->previous()) : ?>
      <li class="pagination-previous"><a href="<?= "dashboard.php?page=" . ($pagination->previous()); ?>" aria-label="Previous page">Previous <span class="show-for-sr">page</span></a></li>
    <?php endif; ?>

    <?php for ($pages = 1; $pages <= $number_of_pages; $pages++) {
      if ($pagination->activePage($pages)) {
        printf('<li class="current"><span class="show-for-sr">You\'re on page</span> %s</li>', $pages);
      } else {
        printf('<li><a href="%s">%s</a></li>', 'dashboard.php?page=' . $pages, $pages);
      }
    } ?>

    <?php if($pagination->next()): ?>
      <li class="pagination-next"><a href="<?= "dashboard.php?page=" . $pagination->next(); ?>" aria-label="Next page">Next <span class="show-for-sr">page</span></a></li>  
    <?php endif; ?>
  </ul>
</nav>