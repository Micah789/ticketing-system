<footer>
  <div class="spacer medium"></div>
  <div class="grid-container">
    <h6 class="text-center">
      &copy; <?= date('Y'); ?> Making Websites Better. All Rights Reserved.
    </h6>
  </div>
  <div class="spacer small"></div>
</footer>
</body>
<!-- end of body tag -->
<script src="https://www.google.com/recaptcha/api.js?render=<?= $_SERVER["RECAPTCHA_SITE_KEY"]; ?>"></script>
<script src="<?= ASSETS_URL . 'js/app.js'; ?>"></script>
</html>