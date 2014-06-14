<?php
require '../../main/Autoloader.php';

use codeminus\util as util;
use codeminus\main as main;
use codeminus\file as file;

main\Autoloader::init();

if (is_readable(main\Application::getConfigPath())) {
  $ini = new file\Ini(main\Application::getConfigPath());
  $dev_env_path = $ini->get('dev_env_path');
  $dev_env_http_path = $ini->get('dev_env_http_path');
  $dev_env_timezone = $ini->get('dev_env_timezone');
  $dev_env_db_host = $ini->get('dev_env_db_host');
  $dev_env_db_user = $ini->get('dev_env_db_user');
  $dev_env_db_pass = $ini->get('dev_env_db_pass');
  $dev_env_db_name = $ini->get('dev_env_db_name');

  $pro_env_path = $ini->get('pro_env_path');
  $pro_env_http_path = $ini->get('pro_env_http_path');
  $pro_env_timezone = $ini->get('pro_env_timezone');
  $pro_env_db_host = $ini->get('pro_env_db_host');
  $pro_env_db_user = $ini->get('pro_env_db_user');
  $pro_env_db_pass = $ini->get('pro_env_db_pass');
  $pro_env_db_name = $ini->get('pro_env_db_name');
} else {
  $dev_env_path = main\Application::getRoot();
  $dev_env_http_path = main\Application::getHttpRoot();
  $dev_env_timezone = date_default_timezone_get();
  $dev_env_db_host = 'localhost';
  $dev_env_db_user = 'root';
  $dev_env_db_pass = '';
  $dev_env_db_name = '';

  $pro_env_path = main\Application::getRoot();
  $pro_env_http_path = main\Application::getHttpRoot();
  $pro_env_timezone = date_default_timezone_get();
  $pro_env_db_host = 'localhost';
  $pro_env_db_user = 'root';
  $pro_env_db_pass = '';
  $pro_env_db_name = '';
}

function timeZoneOptions() {
  foreach (\DateTimeZone::listIdentifiers() as $tz) {
    echo "<option value=\"$tz\">$tz</option>";
  }
}
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>App configuration <?php echo $title ?></title>
    <link rel="shortcut icon" type="image/x-icon" href="../../assets/img/favicon.ico">
    <link rel="stylesheet" href="../../css/codeminus.css" />
  </head>
  <body>
    <div class="container-header">
      <header class="container-centered">
        <img src="../../assets/img/codeminus-php-framework-300x74.png"/>
        <span class="text-bold">v<?php echo main\Framework::VERSION; ?></span>
      </header>
    </div>
    <div class="container-centered">
      <h4>Application Environment Installer</h4>

      <form name="configForm" class="form-input-perline childs-margined-bottom"
            action="output.php" method="POST">
        <div class="row">

          <!-- DEV ENV -->

          <section class="container-box container-alert info rounded span6">
            <section>
              <h5 class="text-normal">Development environment</h5>
              <div class="divider"></div>
              <label>Environment directory:</label>
              <input type="text" name="dev_env_path" class="width-xlarge"
                     value="<?php echo $dev_env_path ?>"
                     id="dev_env_path" />
              <span data-tooltip-for="dev_env_path">
                The absolute path to the application root directory
              </span>

              <label>Environment http path:</label>
              <input type="text" name="dev_env_http_path" class="width-xlarge"
                     value="<?php echo $dev_env_http_path ?>"
                     id="dev_env_http_path" />
              <span data-tooltip-for="dev_env_http_path">
                The absolute application HTTP path
              </span>

              <label>Time zone:</label>
              <select name="dev_env_timezone" class="width-large"
                      data-selected-by-value="<?php echo $dev_env_timezone ?>">
                        <?php timeZoneOptions() ?>
              </select>

              <h6 class="text-normal">Database settings</h6>
              <div class="divider"></div>
              <label>host:</label>
              <input type="text" name="dev_env_db_host" 
                     value="<?php echo $dev_env_db_host ?>" />
              <label>user:</label>
              <input type="text" name="dev_env_db_user"
                     value="<?php echo $dev_env_db_user ?>" />
              <label>password:</label>
              <input type="text" name="dev_env_db_pass"
                     value="<?php echo $dev_env_db_pass ?>" />
              <label for="dev_env_db_name">schema:</label>
              <input type="text" name="dev_env_db_name"
                     value="<?php echo $dev_env_db_name ?>" />
            </section>
          </section>

          <!-- PRO ENV -->

          <section class="container-box container-alert success rounded span6">
            <section>
              <h5 class="text-normal">Production environment</h5>
              <div class="divider"></div>

              <label>Environment directory:</label>
              <input type="text" name="pro_env_path" class="width-xlarge"
                     value="<?php echo $pro_env_path ?>"
                     id="pro_env_path" />
              <span data-tooltip-for="pro_env_path">
                The absolute path to the app root directory
              </span>

              <label>Environment http path:</label>
              <input type="text" name="pro_env_http_path" class="width-xlarge"
                     value="<?php echo $pro_env_http_path ?>"
                     id="pro_env_http_path" />
              <span data-tooltip-for="pro_env_http_path">
                The absolute application HTTP path
              </span>

              <label>Time zone:</label>
              <select name="pro_env_timezone" class="width-large"
                      data-selected-by-value="<?php echo $pro_env_timezone ?>">
                        <?php timeZoneOptions() ?>
              </select>


              <h6 class="text-normal">Database settings</h6>
              <div class="divider"></div>
              <label>host:</label>
              <input type="text" name="pro_env_db_host"
                     value="<?php echo $pro_env_db_host ?>" />
              <label>user:</label>
              <input type="text" name="pro_env_db_user"
                     value="<?php echo $pro_env_db_user ?>" />
              <label>password:</label>
              <input type="text" name="pro_env_db_pass"
                     value="<?php echo $pro_env_db_pass ?>" />
              <label>name:</label>
              <input type="text" name="pro_env_db_name"
                     value="<?php echo $pro_env_db_name ?>" />
            </section>
          </section>
        </div>
        <div class="row">
          <section class="container-box container-alert warning rounded span12">
            <section>
              <p>
                Note that no existing files will be replaced. If you wish to
                reinstall any specific file, delete it first or check one
                the box below
              </p>
              <div class="divider"></div>
              <label>Replace: </label>
              <span class="childs-margined">
                <label>
                  <input type="radio" name="replace" value="none" checked=""/>
                  none
                </label>
                <label>
                  <input type="radio" name="replace" value="config" />
                  only configuration file
                </label>
                <label>
                  <input type="radio" name="replace" value="all" />
                  all files
                </label>
              </span>
            </section>
          </section>
        </div>
        <p class="info">
          Clicking on the button below will create your application's default
          configurations, folders and files.<br/>
        </p>
        <input type="submit" name="install" value="Install application" 
               class="btn-blue " />
      </form>
    </div>
    <script src="../../js/jquery.js"></script>
    <script src="../../js/codeminus.js"></script>
  </body>
</html>