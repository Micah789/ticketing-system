<?php
include 'partials/modules/header.php';
require_once 'core/init.php';

$user = new User();

if ($user->isLoggedIn()) :
  $user->logout();
  Redirect::to('login.php');
else : ?>

  <main>
    <div class="grid-container">
      <div class="spacer large"></div>
      <div class="callout large alert text-center">
        <h3>You have not login yet! <a href="login.php">Login here</a></h3>
      </div>
    </div>
  </main>
<?php endif; 

include 'partials/modules/footer.php';