<?php
if (!isset($_POST['install'])) {
  header('Location: ./');
}

require '../../main/Autoloader.php';

use codeminus\util as util;
use codeminus\main as main;
use codeminus\file as file;

main\Autoloader::init();
?>
<!DOCTYPE html>
<html>
  <head>
    <meta http-equiv="Content-Type" content="text/html; charset=ISO-8859-1">
    <title>App configuration output</title>
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
      <h4>Installing application...</h4>
      <section class="bubble light bordered text-shadow margined-bottom">
        <p>
          <?php
          try {
            util\ClassLog::on();

            $ini = new file\Ini();

            $ini->setKeyPrefix('dev_env_');
            $ini->setDefaultSection('dev_env');
            $ini->set('path', $_POST['dev_env_path']);
            $ini->set('http_path', $_POST['dev_env_http_path']);
            $ini->set('timezone', $_POST['dev_env_timezone']);
            $ini->set('db_host', $_POST['dev_env_db_host']);
            $ini->set('db_user', $_POST['dev_env_db_user']);
            $ini->set('db_pass', $_POST['dev_env_db_pass']);
            $ini->set('db_name', $_POST['dev_env_db_name']);

            $ini->setKeyPrefix('pro_env_');
            $ini->setDefaultSection('pro_env');
            $ini->set('path', $_POST['pro_env_path']);
            $ini->set('http_path', $_POST['pro_env_http_path']);
            $ini->set('timezone', $_POST['pro_env_timezone']);
            $ini->set('db_host', $_POST['pro_env_db_host']);
            $ini->set('db_user', $_POST['pro_env_db_user']);
            $ini->set('db_pass', $_POST['pro_env_db_pass']);
            $ini->set('db_name', $_POST['pro_env_db_name']);

            $ini->setKeyPrefix('view_');
            $ini->setDefaultSection('view');
            $ini->set('dir', '/app/view');
            $ini->set('default_title', '');
            $ini->set('default_header', '/shared/header.php');
            $ini->set('default_footer', '/shared/footer.php');

            $ini->setKeyPrefix('controller_');
            $ini->setDefaultSection('controller');
            $ini->set('dir', '/app/controller');
            $ini->set('index', 'Index');
            $ini->set('error', 'Error');

            $i = new main\Installer($_POST['dev_env_path'], $ini);

            switch($_POST['replace']){
              case 'none':
                $replace = false;
                $replaceConfig = false;
                break;
              case 'all':
                $replace = true;
                $replaceConfig = true;
                break;
              case 'config':
                $replace = false;
                $replaceConfig = true;
            }

            if ($i->createApp($replace, $replaceConfig)) {
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
      <?php
      if (isset($errorMsg)) {
        echo $errorMsg;
      }
      ?>
      <a href="javascript:history.back()" class="btn">go back</a>
      <?php
      if (isset($created)) {
        $ini = new file\Ini(main\Application::getConfigPath());
        ?>
        <a href="<?php echo $ini->get('dev_env_http_path') ?>" id="testApp"
           class="btn btn-blue">Test Installation</a>
      <?php } ?>
    </div>
    <script src="../../js/jquery.js"></script>
    <script src="../../js/codeminus.js"></script>
    <script>
      $('html').animate({scrollTop: $('#testApp').offset().top});
    </script>
  </body>
</html>