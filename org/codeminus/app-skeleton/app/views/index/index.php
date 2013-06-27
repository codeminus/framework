<div class="container-header">
  <div class="container-centered">
    <img src="<?php echo CMF_ASSETS ?>/img/codeminus-php-framework-300x73.png" />
  </div>
</div>
<div class="container-centered childs-block">
  <h3>Welcome to your main page!</h3>
  <section class="container-bubble container-box margined-bottom">
    <header>Your application is ready for you to get started!</header>
    <section class="text-left">
      <p>
        Remember to review your environment configurations inside
        <b>/app/configs/init.php</b>.
      </p>
      <p>
        Go to 
        <a href="https://github.com/codeminus/framework" target="_blank">
          https://github.com/codeminus/framework
        </a>
        to keep updated about the framework.
      </p>
      <p>
        Feel free to contact 
        <a href="https://github.com/codeminus" target="_blank">codeminus</a>
        should you have any questions or comments.
      </p>
    </section>
  </section>
  <section class="container-bubble container-box">
    <header>About this page</header>
    <section class="text-left">
      <p>The files that generated the contents of this view are:</p>
      <ul>
        <?php
        $incFiles = get_included_files();
        foreach ($incFiles as $file) {
          ?>
          <li>
            <span class="text-disabled">
              <?php echo substr($file, 0, strlen(str_replace('/', '\\', APP_PATH))); ?>
            </span>
            <?php echo substr($file, strlen(str_replace('/', '\\', APP_PATH))); ?>

          </li>
          <?php
        }
        ?>
      </ul>
    </section>
  </section>
</div>