<?php
############################################
# CHANGE THIS VIEW ACCORDING TO YOUR NEEDS #
############################################

use codeminus\main as main;
?>
<div class="margined">
  <div class="container-box rounded container-alert error block">
    <section>
      <p class="text-xxlarge">Invalid page request</p>
      <?php if (ENV_MODE == 'dev') { ?>
        <p>
          <b><?php echo ucfirst(main\Router::$CONTROLLER_NAME) ?></b> controller not found in 
          <b><?php echo CONTROLLER_PATH ?></b>
        </p>
      <?php } ?>
    </section>
  </div>
  <p>
      Go to <a href="<?php echo main\Controller::linkTo('index') ?>">main page</a>.
  </p>
</div>