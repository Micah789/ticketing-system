<header>
  <div class="grid-container">
    <ul class="menu dropdown align-center" data-dropdown-menu data-click-open="false" data-parent-link="true">
      <li class="menu-text">Site Title</li>
      <li><a href="dashboard.php">Dashboard</a></li>
      <li>
        <a href="<?= 'profile.php?user='.escape($user->data()->username) ?>">View Profile</a>
        <ul class="menu align-left">
          <li><a href="update.php">Update Profile</a></li>
          <li><a href="changepassword.php">Change Password</a></li>
        </ul>
      </li>

      <?php if($user->hasPermission('admin')): ?>
        <li><a href="archive.php">Archive Tickets</a></li>
      <?php endif; ?>
      
      <li>
        <?php
        printf(
          '<a href="%s">Hello %s</a>',
          'profile.php?user=' . escape($user->data()->username),
          escape($user->data()->nick_name)
        ); ?>
      </li>
      <li><a href="logout.php">Logout</a></li>
    </ul>
  </div>
</header>
<div class="spacer large"></div>