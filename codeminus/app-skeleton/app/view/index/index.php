<?php
use codeminus\main as main;
?>
<div class="container-header">
  <div class="container-centered">
    <img src="<?php echo CMF_ASSETS_PATH ?>/img/codeminus-php-framework-300x74.png" />
    <span class="bold">v<?php echo main\Framework::VERSION; ?></span>
  </div>
</div>
<div class="container-centered childs-block childs-margined-bottom">
  <h3>Welcome to your main page!</h3>
  <section class="container-bubble container-box margined-bottom">
    <header>Your application is ready for you to get started!</header>
    <section class="text-align-left">
      <p>
        Remember to review your environment configurations inside
        <b>/app/config/init.php</b>.
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
  <?php
  echo main\View::about();
  echo main\Framework::appConstantsView();
  ?>
</div>