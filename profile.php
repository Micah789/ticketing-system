<?php include 'partials/modules/header.php';
include 'core/init.php';

$user = new User();

if ($user->isLoggedIn()) {
  include 'partials/components/menu.php';
}

if (!$username = Input::get('user')) :
  Redirect::to('dashboard.php');
else :

  $user = new User($username);

  if (!$user->exists()) {
    Redirect::to(404);
  } else {

    $data = $user->data();
  } ?>

  <main>

    <div class="grid-container">

      <div class="grid-x grid-margin-y text-center">
        <div class="cell">
          <h3><?= $data->nick_name ?></h3>
        </div>

        <div class="cell medium-4">
          <p>Username: <?= $data->username; ?></p>
        </div>

        <?php if (!empty($data->email)) : ?>
          <div class="cell medium-4">
            <p>Email: <?= $data->email; ?></p>
          </div>
        <?php endif; ?>

        <?php if (!empty($data->telephone)) : ?>
          <div class="cell medium-4">
            <p>Telephone: <?= $data->telephone; ?></p>
          </div>
        <?php endif; ?>
      </div>
    </div>

  </main>

<?php endif;

include 'partials/modules/footer.php';
