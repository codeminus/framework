<?php
require_once '../main/Installer.php';
require_once '../file/FileHandler.php';

use \org\codeminus\main as main;

(isset($_POST['cmd'])) ? $title = 'output' : $title = '';
?>
<!DOCTYPE html>
<html>
  <head>
    <title>App configuration <?php echo $title?></title>
    <link rel="shortcut icon" type="image/x-icon" href="../img/favicon.ico">
    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/containers.css" />
    <link rel="stylesheet" href="../css/forms.css" />
  </head>
  <body>
    <div class="container-header">
      <header class="container-centered">
        <img src="../img/codeminus-php-framework-300x73.png" class="float-left"/>
        <div class="float-right bold">main\Installer v1.0</div>
      </header>
    </div>
    <div class="container-centered">
      <section><h4>Application initial configuration</h4></section>
      <?php if (!isset($_POST['cmd'])) { ?>
        <form name="configForm" class="form-input-perline"
              action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
          <input type="hidden" name="DEV_ENVIRONMENT" 
                 value="<?php echo main\Installer::getInvironment() ?>" />
          <section class="container-box rounded block margined-bottom">
            <header>
              General environment settings
            </header>
            <section>
              <label for="DEFAULT_TIMEZONE">Default time zone:</label><br/>
              <input type="text" name="DEFAULT_TIMEZONE" 
                     value="<?php echo date_default_timezone_get() ?>" 
                     id="DEFAULT_TIMEZONE" class="medium" />
              <small>
                Take a look at 
                <a href="http://www.php.net/manual/en/timezones.php" 
                   target="_blank">http://www.php.net/manual/en/timezones.php</a>
                for a list of supported time zones
              </small>
            </section>
          </section>
          <section class="container-box rounded block margined-bottom">
            <header>
              Development environment
            </header>
            <section>
              <label for="DEV_ENVIRONMENT">Environment directory:</label>
              <input type="text" disabled 
                     value="<?php echo main\Installer::getInvironment() ?>"
                     id="DEV_ENVIRONMENT" class="medium" />
              <small>
                The development environment directory is on the same level of 
                the framework package.                            
              </small>
            </section>
            <section>
              <h6>Database settings</h6>
              <label for="DEV_DB_HOST">Database host:</label>
              <input type="text" name="DEV_DB_HOST" value="localhost" 
                     id="DEV_DB_HOST" class="medium" />
              <label for="DEV_DB_USER">Database user:</label>
              <input type="text" name="DEV_DB_USER" value="root" 
                     id="DEV_DB_USER" class="medium" />
              <label for="DEV_DB_PASS">Database password:</label>
              <input type="text" name="DEV_DB_PASS" value="" 
                     id="DEV_DB_PASS" class="medium" />
              <label for="DEV_DB_NAME">Database name:</label>
              <input type="text" name="DEV_DB_NAME" value="" 
                     id="DEV_DB_NAME" class="medium" />
            </section>
          </section>
          <p class="info">
                Clicking on the button below will create your app's default configurations,
                folders and files:
              </p>
              <p class="warning">
              Note that no existing files will be replaced. If you wish to
              recreate any specific file, delete it first.
              </p>
              <input type="submit" name="cmd" value="set application configurations" 
                     class="btn blue " />
        </form>

      <?php } else { ?>

        <section class="container-box rounded block">
          <header>Output</header>
          <section>
            <?php
            $i = new main\Installer();
            $i->createAppFiles();
            ?>
          </section>
          <section>
            <a href="javascript:history.back()" class="btn">go back</a>
            <a href="<?php echo main\Installer::APP_ROOT ?>" class="btn blue">Test Installation</a>
          </section>
        </section>

      <?php } ?>

    </div>
    <script src="../js/jquery.js"></script>
    <script type="text/javascript" src="../js/codeminus.js"></script>
  </body>
</html>