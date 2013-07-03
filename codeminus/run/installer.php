<?php
require '../main/ExtendedException.php';
require '../util/ClassLog.php';
require '../main/Installer.php';
require '../file/Directory.php';
require '../file/File.php';

use codeminus\util as util;
use codeminus\main as main;

(isset($_POST['cmd'])) ? $title = 'output' : $title = '';
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
        <img src="../assets/img/codeminus-php-framework-300x73.png"
             class="float-left"/>
        <div class="float-right bold">main\Installer v1.0</div>
      </header>
    </div>
    <div class="container-centered">
      <?php if (!isset($_POST['cmd'])) { ?>
        <section><h4>Application initial configuration</h4></section>
        <form name="configForm" class="form-input-perline"
              action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
          <input type="hidden" name="dev_environment" 
                 value="<?php echo main\Installer::getFrameworkPath() ?>" />
          <section class="container-box rounded block margined-bottom">
            <header>
              General environment settings
            </header>
            <section>
              <label for="default_timezone">Default time zone:</label><br/>
              <input type="text" name="default_timezone" 
                     value="<?php echo date_default_timezone_get() ?>" 
                     id="default_timezone" class="medium" />
              <small>
                Take a look at 
                <a href="http://www.php.net/manual/en/timezones.php" 
                   target="_blank">
                  http://www.php.net/manual/en/timezones.php
                </a>
                for a list of supported time zones
              </small>
            </section>
          </section>
          <section class="container-box rounded block margined-bottom">
            <header>
              Development environment
            </header>
            <section>
              <label for="dev_environment">Environment directory:</label>
              <input type="text" disabled 
                     value="<?php echo main\Installer::getFrameworkPath() ?>"
                     id="dev_environment" class="medium" />
              <small>
                The development environment directory is on the same level of 
                the framework package.                            
              </small>
            </section>
            <section>
              <h6>Database settings</h6>
              <label for="dev_db_host">Database host:</label>
              <input type="text" name="dev_db_host" value="localhost" 
                     id="dev_db_host" class="medium" />
              <label for="dev_db_user">Database user:</label>
              <input type="text" name="dev_db_user" value="root" 
                     id="dev_db_user" class="medium" />
              <label for="dev_db_pass">Database password:</label>
              <input type="text" name="dev_db_pass" value="" 
                     id="dev_db_pass" class="medium" />
              <label for="dev_db_name">Database name:</label>
              <input type="text" name="dev_db_name" value="" 
                     id="dev_db_name" class="medium" />
            </section>
          </section>
          <p class="info">
            Clicking on the button below will create your app's default
            configurations, folders and files:
          </p>
          <p class="warning">
            Note that no existing files will be replaced. If you wish to
            reinstall any specific file, delete it first or check the box below:
          </p>
          <p class="warning">
            <input type="checkbox" name="reinstall" id="reinstall" value="1" />
            <label for="reinstall">replace all existent files.</label>
          </p>

          <input type="submit" name="cmd" value="set application configurations" 
                 class="btn-blue " />
        </form>

      <?php } else { ?>
        <section><h4>Application initial configuration output</h4></section>
        <section class="bubble light bordered text-shadow">
          <p>
            <?php
            try {
              util\ClassLog::turnOn();
              $i = new main\Installer();
              $i->setDevEnvironment($_POST['dev_environment']);
              $i->setDevDbInfo($_POST['dev_db_host'], $_POST['dev_db_user'], $_POST['dev_db_pass'], $_POST['dev_db_name']);
              $i->setDefaultTimeZone($_POST['default_timezone']);

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
              echo $e->getMessage();
            }
            ?>
          </p>
        </section>

        <a href="javascript:history.back()" class="btn">go back</a>
        <?php if (isset($created)) { ?>
          <a href="<?php echo $i->getFrameworkHttpPath() ?>" id="testApp" class="btn btn-blue">Test Installation</a>
        <?php } ?>
      <?php } ?>
    </div>
    <script src="../js/jquery.js"></script>
    <script src="../js/codeminus.js"></script>
    <script>
      $('html').animate({ scrollTop : $('#testApp').offset().top});
    </script>
  </body>
</html>