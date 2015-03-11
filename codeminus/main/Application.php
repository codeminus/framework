<?php

namespace codeminus\main;

use codeminus\file as file;

/**
 * Codemius Framework Application 
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */
class Application {

  //use \codeminus\traits\Singleton;

  const DEV_MODE = 0;
  const PRO_MODE = 1;

  /**
   * Initializes the application environment constants<br/>
   * Initializes the application router<br/>
   * Initializes the application class autoloader
   * @param int $mode One of the \codeminus\main\Application constants
   * @return void
   */
  public static function init($mode = self::DEV_MODE, $configFile = null) {

    require 'codeminus/main/Autoloader.php';
    Autoloader::init();

    if (!isset($configFile)) {
      $ini = new file\Ini('app/config/main.ini');
    } else {
      $ini = new file\Ini($configFile);
    }

    switch ($mode) {
      case self::DEV_MODE:
        error_reporting(E_ALL);
        $ini->setKeyPrefix('dev_env_');
        break;
      case self::PRO_MODE:
        error_reporting(0);
        ini_set('display_errors', 0);
        $ini->setKeyPrefix('pro_env_');
        break;
      default :
        throw new ExtendedException('Invalid environment mode. Please specify a valid one.'
        , E_ERROR);
    }

    define('APP_PATH', $ini->get("path"));
    define('APP_HTTP_PATH', $ini->get("http_path"));
    define('APP_ENV_PATH', str_replace($_SERVER['DOCUMENT_ROOT'], '', APP_PATH));

    Autoloader::includePath(APP_PATH);

    if (!is_dir(APP_PATH)) {
      throw new ExtendedException('Invalid environment directory.' . APP_PATH
      . ' does not exist.', E_ERROR);
    }

    define('ENV_MODE', $mode);

    date_default_timezone_set($ini->get('timezone'));

    define('DB_HOST', $ini->get('db_host'));
    define('DB_USER', $ini->get('db_user'));
    define('DB_PASS', $ini->get('db_pass'));
    define('DB_NAME', $ini->get('db_name'));

    $ini->setKeyPrefix(null);

    define('VIEW_PATH', APP_PATH . $ini->get('view_dir'));
    define('VIEW_DEFAULT_TITLE', $ini->get('view_default_title'));
    define('VIEW_DEFAULT_HEADER', $ini->get('view_default_header'));
    define('VIEW_DEFAULT_FOOTER', $ini->get('view_default_footer'));

    define('CONTROLLER_PATH', APP_PATH . $ini->get('controller_dir'));
    define('INDEX_CONTROLLER', $ini->get('controller_index'));
    define('ERROR_CONTROLLER', $ini->get('controller_error'));


    define('CMF_PATH', APP_ENV_PATH . '/codeminus');
    define('CMF_ASSETS_PATH', CMF_PATH . '/assets');
    define('CMF_CSS_PATH', CMF_PATH . '/css');
    define('CMF_JS_PATH', CMF_PATH . '/js');

    if (ENV_MODE == self::PRO_MODE) {
      file\File::create(APP_PATH . '/codeminus/run/.htaccess', 'deny from all');
    } else {
      file\File::delete(APP_PATH . '/codeminus/run/.htaccess');
    }

    //Initializing path router
    try {
      new Router();
    } catch (ExtendedException $e) {
      echo $e->getFormattedMessage();
      exit;
    }
  }

  /**
   * Returns the application root, considering that the framework will always
   * be on app_root/codeminus folder
   * @return string
   */
  public static function getRoot() {
    return file\Directory::normalize(substr(__DIR__, 0, strpos(__DIR__, DIRECTORY_SEPARATOR . 'codeminus')));
  }

  /**
   * Returns the application http root, considering that the framework will
   * always be on app_root/codeminus folder 
   * @return string
   */
  public static function getHttpRoot() {
    return 'http://' . $_SERVER['HTTP_HOST'] . substr($_SERVER['REQUEST_URI'], 0, strpos($_SERVER['REQUEST_URI'], '/codeminus'));
  }

  /**
   * Returns the path to the application's ini configuration file
   * @return string
   */
  public static function getConfigPath() {
    return file\Directory::normalize(self::getRoot()) . '/app/config/main.ini';
  }

}
