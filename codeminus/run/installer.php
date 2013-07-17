<?php
require '../main/Autoloader.php';

use codeminus\util as util;
use codeminus\main as main;

main\Autoloader::init();

if (isset($_POST['cmd'])) {
  $title = 'output';
} else {
  $title = '';
}

function timeZoneOptions() {
  foreach (\DateTimeZone::listIdentifiers() as $tz) {
    if ($tz == date_default_timezone_get()) {
      $selected = "selected";
    } else {
      $selected = null;
    }
    echo "<option value=\"$tz\" $selected >$tz</option>";
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>App configuration <?php echo $title ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="../assets/img/favicon.ico">
    <link rel="stylesheet" href="../css/codeminus.css" />
  </head>
  <body>
    <div class="container-header">
      <header class="container-centered">
        <img src="../assets/img/codeminus-php-framework-300x74.png"/>
        <span class="text-bold">v<?php echo main\Framework::VERSION; ?></span>
      </header>
    </div>
    <div class="container-centered">
      <section><h4>App environment installer</h4></section>
      <?php if (!isset($_POST['cmd'])) { ?>
        <form name="configForm" class="form-input-perline childs-margined-bottom"
              action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
          <div class="row">

            <!-- DEV ENV -->

            <section class="container-box container-alert info rounded span5">
              <section>
                <h5 class="text-normal">Development environment</h5>
                <div class="divider"></div>
                <input type="hidden" name="dev_env_path" 
                       value="<?php echo main\Installer::getFrameworkRoot() ?>" />
                <label for="dev_env_path">Environment directory:</label>
                <input type="text" disabled 
                       value="<?php echo main\Installer::getFrameworkRoot() ?>"
                       id="dev_env_path" />
                <small>
                  The absolute path to the app root directory
                </small><br/><br/>
                <label for="dev_env_timezone">Time zone:</label><br/>
                <select name="dev_env_timezone" id="dev_env_timezone">
                  <?php timeZoneOptions() ?>
                </select>
                <h6 class="text-normal">Database settings</h6>
                <div class="divider"></div>
                <label for="dev_env_db_host">host:</label>
                <input type="text" name="dev_env_db_host" value="localhost" 
                       id="dev_env_db_host" class="medium" />
                <label for="dev_env_db_user">user:</label>
                <input type="text" name="dev_env_db_user" value="root" 
                       id="dev_env_db_user" class="medium" />
                <label for="dev_env_db_pass">password:</label>
                <input type="text" name="dev_env_db_pass" value="" 
                       id="dev_env_db_pass" class="medium" />
                <label for="dev_env_db_name">name:</label>
                <input type="text" name="dev_env_db_name" value="" 
                       id="dev_env_db_name" class="medium" />
              </section>
            </section>

            <!-- PRO ENV -->

            <section class="container-box container-alert success rounded span5">
              <section>
                <h5 class="text-normal">Production environment</h5>
                <div class="divider"></div>

                <label for="pro_env_path">Environment directory:</label>
                <input type="text" name="pro_env_path"
                       value="<?php echo main\Installer::getFrameworkRoot() ?>"
                       id="pro_env_path" />
                <small>
                  The absolute path to the app root directory
                </small><br/><br/>
                <label for="pro_env_timezone">Time zone:</label><br/>
                <select name="pro_env_timezone" id="pro_env_timezone">
                  <?php timeZoneOptions() ?>
                </select>
                <h6 class="text-normal">Database settings</h6>
                <div class="divider"></div>
                <label for="pro_env_db_host">host:</label>
                <input type="text" name="pro_env_db_host" value="localhost" 
                       id="pro_env_db_host" class="medium" />
                <label for="pro_env_db_user">user:</label>
                <input type="text" name="pro_env_db_user" value="root" 
                       id="pro_env_db_user" class="medium" />
                <label for="pro_env_db_pass">password:</label>
                <input type="text" name="pro_env_db_pass" value="" 
                       id="pro_env_db_pass" class="medium" />
                <label for="pro_env_db_name">name:</label>
                <input type="text" name="pro_env_db_name" value="" 
                       id="pro_env_db_name" class="medium" />
              </section>
            </section>
          </div>
          <p class="info">
            Clicking on the button below will create your app's default
            configurations, folders and files.<br/>
            <span class="warning">
              Note that no existing files will be replaced. If you wish to
              reinstall any specific file, delete it first or check the box below:
            </span>
          <p class="warning">
            <input type="checkbox" name="reinstall" id="reinstall" value="1" />
            <label for="reinstall">replace all existent files.</label>
          </p>

          <input type="submit" name="cmd" value="Install application" 
                 class="btn-blue " />
        </form>

      <?php } else { ?>

        <section class="bubble light bordered text-shadow margined-bottom">
          <p>
            <?php
            try {
              util\ClassLog::on();
              $i = new main\Installer($_POST['dev_env_path']);

              $i->addConfig('dev_env', 'path', $_POST['dev_env_path']);
              $i->addConfig('dev_env', 'timezone', $_POST['dev_env_timezone']);
              $i->addConfig('dev_env', 'db_host', $_POST['dev_env_db_host']);
              $i->addConfig('dev_env', 'db_user', $_POST['dev_env_db_user']);
              $i->addConfig('dev_env', 'db_pass', $_POST['dev_env_db_pass']);
              $i->addConfig('dev_env', 'db_name', $_POST['dev_env_db_name']);

              $i->addConfig('pro_env', 'path', $_POST['pro_env_path']);
              $i->addConfig('pro_env', 'timezone', $_POST['pro_env_timezone']);
              $i->addConfig('pro_env', 'db_host', $_POST['pro_env_db_host']);
              $i->addConfig('pro_env', 'db_user', $_POST['pro_env_db_user']);
              $i->addConfig('pro_env', 'db_pass', $_POST['pro_env_db_pass']);
              $i->addConfig('pro_env', 'db_name', $_POST['pro_env_db_name']);

              $i->addConfig('view', 'dir', '/app/view');
              $i->addConfig('view', 'default_title', '');
              $i->addConfig('view', 'default_header', '/shared/header.php');
              $i->addConfig('view', 'default_footer', '/shared/footer.php');

              $i->addConfig('controller', 'dir', '/app/controller');
              $i->addConfig('controller', 'index', 'Index');
              $i->addConfig('controller', 'error', 'Error');

              (isset($_POST['reinstall'])) ? $reinstall = true : $reinstall = false;

              if ($i->createApp($reinstall)) {
                foreach (util\ClassLog::$logs as $log) {
                  switch ($log['type']) {
                    case 0:
                      $class = 'info';
                      break;
                    case 1:
                      $class = 'warning';
                      break;
                    case 2:
                      $class = 'error';
                      break;
                  }
                  echo '<span class="' . $class . '">' . $log['message'] . '</span><br/>';
                }
              }

              $created = true;
            } catch (main\ExtendedException $e) {
              $errorMsg = $e->getFormattedMessage();
            }
            ?>
          </p>
        </section>
        <?php if(isset($errorMsg)){ echo $errorMsg; }?>
        <a href="javascript:history.back()" class="btn">go back</a>
        <?php if (isset($created)) { ?>
          <a href="<?php echo $i->getFrameworkHttpRoot() ?>" id="testApp"
             class="btn btn-blue">Test Installation</a>
        <?php } ?>
      <?php } ?>
    </div>
    <script src="../js/jquery.js"></script>
    <script src="../js/codeminus.js"></script>
    <script>
      $('html').animate({scrollTop: $('#testApp').offset().top});
    </script>
  </body>
</html>