<?php include get_theme_file_path('inc/variables.php'); ?>


<footer class="uw-footer">

  <div class="uw-container">
    <!-- Top: Logo & Legal -->


    <!-- Middle: Info & Contact -->
    <div class="uw-footer-middle">
      <a href="<?php echo home_url('/'); ?>" class="uw-footer-logo">
        <img src="<?php echo get_theme_file_uri('/assets/images/logo.png'); ?>" alt="STWORKS Logo">
      </a>
      <div class="uw-footer-contact">
        <?php if ($telephone) { ?>
          <span class="uw-footer-phone"><?php echo $telephone; ?></span>
        <?php } ?>
        <div class="uw-footer-contact-detail">
          <div class="uw-footer-contact-row">
            <?php if ($fax) { ?>
              <span class="uw-footer-contact-label">Fax.</span>
              <span><?php echo $fax; ?></span>
            <?php } ?>
          </div>
        </div>
      </div>
    </div>
    <div class="uw-footer-info">
      <div class="uw-footer-info-row">
        <?php if ($ceo) { ?>
          <span><?php echo $ceo; ?></span>
        <?php } ?>
        <?php if ($company_name) { ?>
          <span><?php echo $company_name; ?></span>
        <?php } ?>
      </div>
      <div class="uw-footer-info-row">
        <?php if ($address) { ?>
          <span><?php echo $address; ?></span>
        <?php } ?>
        <?php if ($business_registration_number) { ?>
          <span><?php echo $business_registration_number; ?></span>
        <?php } ?>
        <?php if ($corporate_registration_number) { ?>
          <span><?php echo $corporate_registration_number; ?></span>
        <?php } ?>
      </div>
    </div>
    <!-- Bottom: Copyright -->
    <div class="uw-footer-bottom">
      <p class="uw-footer-copyright">© 2025 STWORKS Corp. ALL RIGHTS RESERVED.</p>
    </div>
  </div>
</footer>

<?php wp_footer(); ?>

</body>

</html>