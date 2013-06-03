<?php
require_once '../main/Installer.php';
require_once '../file/FileHandler.php';

use \org\codeminus\main as main;
?>

<!DOCTYPE html>
<html>
  <head>
    <?php if (!isset($_POST['cmd'])) { ?>
      <title>App configuration</title>
    <?php } else { ?>
      <title>App configuration - output</title>
    <?php } ?>
    <link rel="stylesheet" href="../css/base.css" />
    <link rel="stylesheet" href="../css/containers.css" />
    <link rel="stylesheet" href="../css/forms.css" />
    <style type="text/css">
      .container-box{
        margin-bottom: 20px;
      }
    </style>
  </head>
  <body>
    <div class="container-header">
      <header class="container-centered clearfix">
        <img src="../img/cmf-medium.png" class="float-left"/>
        <div class="float-right bold">main\Installer v1.0</div>
      </header>
    </div>
    <div class="container-centered">
      <section><h4>Application initial configuration</h4></section>
      <?php if (!isset($_POST['cmd'])) { ?>
        <form name="configForm" action="<?php echo $_SERVER['PHP_SELF'] ?>" method="POST">
          <input type="hidden" name="DEV_ENVIRONMENT" value="<?php echo main\Installer::getInvironment() ?>" />
          <section class="container-box block">
            <header>
              General environment settings
            </header>
            <section>
              <label for="DEFAULT_TIMEZONE">Default time zone:</label>
              <input type="text" name="DEFAULT_TIMEZONE" value="<?php echo date_default_timezone_get() ?>" id="DEFAULT_TIMEZONE" class="medium" />
              Take a look at <a href="http://www.php.net/manual/en/timezones.php" target="_blank">http://www.php.net/manual/en/timezones.php</a>
              for a list of supported time zones
            </section>
          </section>
          <section class="container-box block">
            <header>
              Development environment
            </header>
            <section>
              <label for="DEV_ENVIRONMENT">Environment directory:</label>
              <input type="text" disabled value="<?php echo main\Installer::getInvironment() ?>" id="DEV_ENVIRONMENT" class="medium" />
              The development environment directory is on the same level of the framework package.                            
            </section>
            <section>
              <h6>Database settings</h6>

              <label for="DEV_DB_HOST">Database host:</label>
              <input type="text" name="DEV_DB_HOST" value="localhost" id="DEV_DB_HOST" class="medium" />
              <label for="DEV_DB_USER">Database user:</label>
              <input type="text" name="DEV_DB_USER" value="root" id="DEV_DB_USER" class="medium" />
              <label for="DEV_DB_PASS">Database password:</label>
              <input type="text" name="DEV_DB_PASS" value="" id="DEV_DB_PASS" class="medium" />
              <label for="DEV_DB_NAME">Database name:</label>
              <input type="text" name="DEV_DB_NAME" value="" id="DEV_DB_NAME" class="medium" />
            </section>
          </section>
          <section class="container-box block">
            <header>
              Production environment
            </header>
            <section>
              <label for="PRO_ENVIRONMENT">Environment directory:</label>
              <input type="text" name="PRO_ENVIRONMENT" value="" id="PRO_ENVIRONMENT" class="medium" />
            </section>
            <section>
              <h6>Database settings</h6>

              <label for="PRO_DB_HOST">Database host:</label>
              <input type="text" name="PRO_DB_HOST" value="" id="PRO_DB_HOST" class="medium" />
              <label for="PRO_DB_USER">Database user:</label>
              <input type="text" name="PRO_DB_USER" value="" id="PRO_DB_USER" class="medium" />
              <label for="PRO_DB_PASS">Database password:</label>
              <input type="text" name="PRO_DB_PASS" value="" id="PRO_DB_PASS" class="medium" />
              <label for="PRO_DB_NAME">Database name:</label>
              <input type="text" name="PRO_DB_NAME" value="" id="PRO_DB_NAME" class="medium" />
            </section>
          </section>
          <section class="container-box block">
            <header>
              Almost there!
            </header>
            <section>
              Clicking on install will create your app's default configurations, folders and files:
              <input type="submit" name="cmd" value="install" class="btn btn-blue" />
              <br/>Note that no existing files will be replaced, so, if you wish to recreate any specific file, delete it first.
            </section>
          </section>
        </form>

      <?php } else { ?>

        <section class="container-box block">
          <header>Output</header>
          <section>
            <?php
            $i = new main\Installer();
            $i->createAppFiles();
            ?>
          </section>
          <section>
            <a href="javascript:history.back()" class="btn btn-blue">go back</a>
            <a href="<?php echo main\Installer::APP_ROOT ?>" class="btn btn-blue">Test Installation</a>
          </section>
        </section>

      <?php } ?>

    </div>
  </body>
</html>