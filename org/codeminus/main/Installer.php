<?php

namespace org\codeminus\main;

use \org\codeminus\file as file;

/**
 * Framework Installer
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */
class Installer {

  const APP_ROOT = '../../..';

  /**
   * @return Installer
   */
  public function __construct() {
    
  }

  /**
   * Create application's default files and folders
   * @return void
   */
  public function createAppFiles() {
    file\FileHandler::recursiveCopy('../app-skeleton',  self::APP_ROOT);    
    $initFilePath = self::APP_ROOT . '/app/configs/init.php';    
    file\FileHandler::createFile($initFilePath, $this->getInitContent());
  }

  /**
   * Returns the Application's environment assuming that the framework package
   * has the same root folder has app
   * @return string
   * @todo implement a more dynamic solution
   */
  public static function getInvironment() {
    return str_replace('/org/codeminus/run/installer.php', '', $_SERVER['SCRIPT_NAME']);
  }

  /**
   * Generates the content to the app/configs/init.php main configuration file
   * @return string
   */
  public function getInitContent() {

    $DEV_ENVIRONMENT = (isset($_POST['DEV_ENVIRONMENT'])) ? $_POST['DEV_ENVIRONMENT'] : '';
    $PRO_ENVIRONMENT = (isset($_POST['PRO_ENVIRONMENT'])) ? $_POST['PRO_ENVIRONMENT'] : '';
    $VIEW_DEFAULT_TITLE = (isset($_POST['VIEW_DEFAULT_TITLE'])) ? $_POST['VIEW_DEFAULT_TITLE'] : '';

    $DEV_DB_HOST = (isset($_POST['DEV_DB_HOST'])) ? $_POST['DEV_DB_HOST'] : '';
    $DEV_DB_USER = (isset($_POST['DEV_DB_USER'])) ? $_POST['DEV_DB_USER'] : '';
    $DEV_DB_PASS = (isset($_POST['DEV_DB_PASS'])) ? $_POST['DEV_DB_PASS'] : '';
    $DEV_DB_NAME = (isset($_POST['DEV_DB_NAME'])) ? $_POST['DEV_DB_NAME'] : '';

    $PRO_DB_HOST = (isset($_POST['PRO_DB_HOST'])) ? $_POST['PRO_DB_HOST'] : '';
    $PRO_DB_USER = (isset($_POST['PRO_DB_USER'])) ? $_POST['PRO_DB_USER'] : '';
    $PRO_DB_PASS = (isset($_POST['PRO_DB_PASS'])) ? $_POST['PRO_DB_PASS'] : '';
    $PRO_DB_NAME = (isset($_POST['PRO_DB_NAME'])) ? $_POST['PRO_DB_NAME'] : '';

    $DEFAULT_TIMEZONE = (isset($_POST['DEFAULT_TIMEZONE'])) ? $_POST['DEFAULT_TIMEZONE'] : '';

    $initFile = <<<FILE
<?php
/**
 * Codeminus Framework Initializer
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 3.0
 */

/**
 * Development environment directory
 * @var string path relative to domain root directory
 */
const DEV_ENVIRONMENT = '$DEV_ENVIRONMENT';

/**
 * Production environment directory
 * @var string path relative to domain root directory
 */
const PRO_ENVIRONMENT = '$PRO_ENVIRONMENT';

/**
 * Controllers directory 
 * It is recommended that you don't change this location
 * @var string path relative to application root directory
 */
const CONTROLLER_DIR = '/app/controllers';

/**
 * Views directory
 * It is recommended that you don't change this location
 * @var string path relative to application root directory
 */
const VIEW_DIR = '/app/views';

/**
 * Default page title
 * @var string
 * @see org/codeminus/main/View.php for more information
 */
const VIEW_DEFAULT_TITLE = '$VIEW_DEFAULT_TITLE';

/**
 * File to be included before the view file. When requested
 * @var string path relative to VIEW_DIR
 */
const VIEW_DEFAULT_HEADER = '/shared/header.php';

/**
 * File to be included after the view file. When requested
 * @var string path relative to VIEW_DIR
 */
const VIEW_DEFAULT_FOOTER = '/shared/footer.php';

/**
 * The default controller to load when no controller is defined
 * It must be inside CONTROLLER_DIR
 * @var string
 */
const INDEX_CONTROLLER = 'Index';

/**
 * The controller to be called when an invalid controller is requested
 * It must be inside CONTROLLER_DIR
 * @var string
 */
const ERROR_CONTROLLER = 'Error';

//Development Database configuration
const DEV_DB_HOST = '$DEV_DB_HOST';
const DEV_DB_USER = '$DEV_DB_USER';
const DEV_DB_PASS = '$DEV_DB_PASS';
const DEV_DB_NAME = '$DEV_DB_NAME';

//Production Database configuration
const PRO_DB_HOST = '$PRO_DB_HOST';
const PRO_DB_USER = '$PRO_DB_USER';
const PRO_DB_PASS = '$PRO_DB_PASS';
const PRO_DB_NAME = '$PRO_DB_NAME';

/**
 * Default timezone
 * @var string timezone  supported by php
 * @see http://www.php.net/manual/en/timezones.php for a list of supported timezones
 */
const DEFAULT_TIMEZONE = '$DEFAULT_TIMEZONE';

/**
 * Default records per page on a result listing
 * @var int records per page
 */
const DEFAULT_RPP = 20;

/**
 * Default email address to be used as default sender on org\codeminus\file\Email
 * @var string email address
 */
const DEFAULT_EMAILSENDER_ADDRESS = '';

/**
 * Default email owner's name to be used as default sender on org\codeminus\file\Email
 * @var string email owner's name
 */
const DEFAULT_EMAILSENDER_NAME    = '';

/**
 * Call the init function and set the application environment
 * Change the parameter according to environment
 */
init(DEV_ENVIRONMENT);


###############################################################################
# DO NOT CHANGE BELOW THIS LINE
###############################################################################


function init(\$environment){    
    
    date_default_timezone_set(DEFAULT_TIMEZONE);
    
    \$path = \$_SERVER['DOCUMENT_ROOT'].\$environment;

    if(!is_dir(\$path)){
        exit('<b>Fatal error:</b> Invalid environment directory. Path: '.\$path.' does not exist.');
    }
    
    define('APP_ENVIRONMENT_PATH',\$environment);
    define('APP_PATH', \$path);    
    define('APP_HTTP_PATH', 'http://'.\$_SERVER['HTTP_HOST'].\$environment);
    
    define('CONTROLLER_PATH', APP_PATH.CONTROLLER_DIR);
    define('VIEW_PATH', APP_PATH.VIEW_DIR);
    
    define('LIB_IMAGES_PATH', \$environment.'/org/codeminus/img');
    define('LIB_ICONS_PATH', \$environment.'/org/codeminus/img/icon');
    define('LIB_CSS_PATH', \$environment.'/org/codeminus/css');
    
    switch (\$environment){

        case DEV_ENVIRONMENT:

            error_reporting(E_ALL);
            define('DB_HOST',  DEV_DB_HOST);
            define('DB_USER',  DEV_DB_USER);
            define('DB_PASS',  DEV_DB_PASS);
            define('DB_NAME',  DEV_DB_NAME);

            break;

        case PRO_ENVIRONMENT:

            error_reporting(0);
            ini_set('display_errors',0);
            
            define('DB_HOST',  PRO_DB_HOST);
            define('DB_USER',  PRO_DB_USER);
            define('DB_PASS',  PRO_DB_PASS);
            define('DB_NAME',  PRO_DB_NAME);

            break;

        default :
            exit('<b>Fatal error:</b> Invalid environment directory. Please specify a valid enviroment.');
    }

}

spl_autoload_register(function(\$className){
    require_once APP_PATH . '/' .  str_replace('\\\', '/', \$className).'.php';
});


use \org\codeminus\main as main;

//Initializing path router
try{
    new main\Router();
}  catch (main\ExtException \$e){
    echo \$e->getMessage();
    exit;
}
FILE;

    return $initFile;
  }

}