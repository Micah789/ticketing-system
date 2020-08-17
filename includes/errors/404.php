<?php include 'partials/modules/header.php'; ?>
<?php require_once 'core/init.php'; ?>

<main>
  <div class="grid-container">
    <div class="spacer"></div>
    <h1>Not Found</h1>
    <h4><?php echo $_SERVER['REQUEST_URI']; ?> doe not exist, sorry.</h4>
    <?php $refuri = parse_url($_SERVER['HTTP_REFERER']); // use the parse_url() function to create an array containing information about the domain
    if ($refuri['host'] == "localhost") {
      echo "You should email dev@mwb_agency.com and tell me I have a dead link on this site.";
    } ?>
    <div class="spacer"></div>
  </div>
</main>

<?php include 'partials/modules/footer.php'; ?>