<?php

namespace org\codeminus\main;

use org\codeminus\main as main;
use org\codeminus\file as file;

/**
 * Framework Installer
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.2
 */
class Installer {

  private $appRoot;
  private $devEnvironment;
  private $proEnvironment;
  private $devDbInfo = array();
  private $proDbInfo = array();
  private $defaultTimeZone;
  private $defaultViewTitle;
  
  /**
   * Framework Installer
   * @return Installer
   */
  public function __construct($appRoot = null) {
    if (isset($appRoot)) {
      $this->setAppRoot($appRoot);
    } else {
      $this->setAppRoot(substr($_SERVER['SCRIPT_FILENAME'], 0, strpos($_SERVER['SCRIPT_FILENAME'], '/org')));
    }
    
    $this->setDefaultTimeZone(date_default_timezone_get());
    
    $this->proDbInfo['host'] = null;
    $this->proDbInfo['user'] = null;
    $this->proDbInfo['pass'] = null;
    $this->proDbInfo['name'] = null;
    
  }

  /**
   * Returns the framework's root directory
   * @return string
   */
  public static function getFrameworkPath() {
    return substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/org'));
  }

  /**
   * Application HTTP path
   * @return string
   */
  public function getFrameworkHttpPath() {
    return 'http://' . $_SERVER['HTTP_HOST'] . $this->getFrameworkPath();
  }
  
  /**
   * Path for the application's installation
   * @return string
   */
  public function getAppRoot() {
    return $this->appRoot;
  }

  /**
   * Defines the path for the application's installation
   * @param string $appRoot
   * @return void
   */
  private function setAppRoot($appRoot) {
    $this->appRoot = $appRoot;
  }

  /**
   * Application's development environment directory
   * @return string
   */
  public function getDevEnvironment() {
    return $this->devEnvironment;
  }

  /**
   * Defines the application's development environment directory
   * @param string $devEnvironment
   * @return void
   */
  public function setDevEnvironment($devEnvironment) {
    $this->devEnvironment = $devEnvironment;
  }

  /**
   * Application's production environment directory
   * @return string
   */
  public function getProEnvironment() {
    return $this->proEnvironment;
  }

  /**
   * Defines the application's production environment directory
   * @param string $proEnvironment
   * @return void
   */
  public function setProEnvironment($proEnvironment) {
    $this->proEnvironment = $proEnvironment;
  }

  /**
   * An associative array containing the application's development environment
   * database informations:<br/>
   * ['host']
   * ['user']
   * ['pass']
   * ['name']
   * @return array
   */
  public function getDevDbInfo() {
    return $this->devDbInfo;
  }

  /**
   * Defines the application's development environment database informations
   * @param string $host database host
   * @param string $user database user
   * @param string $pass database user's password
   * @param string $database database name
   * @return void
   */
  public function setDevDbInfo($host, $user, $pass, $database) {
    $this->devDbInfo['host'] = $host;
    $this->devDbInfo['user'] = $user;
    $this->devDbInfo['pass'] = $pass;
    $this->devDbInfo['name'] = $database;
  }

  /**
   * An associative array containing the application's production environment
   * database informations:<br/>
   * ['host']
   * ['user']
   * ['pass']
   * ['name']
   * @return array
   */
  public function getProDbInfo() {
    return $this->proDbInfo;
  }

  /**
   * Defines the application's production environment database informations
   * @param string $host database host
   * @param string $user database user
   * @param string $pass database user's password
   * @param string $database database name
   * @return void
   */
  public function setProDbInfo($host, $user, $pass, $database) {
    $this->proDbInfo['host'] = $host;
    $this->proDbInfo['user'] = $user;
    $this->proDbInfo['pass'] = $pass;
    $this->proDbInfo['name'] = $database;
  }

  /**
   * Application's default timezone
   * @return string
   */
  public function getDefaultTimeZone() {
    return $this->defaultTimeZone;
  }

  /**
   * Defines the application's default timezone
   * @param type $defaultTimeZone
   */
  public function setDefaultTimeZone($defaultTimeZone) {
    $this->defaultTimeZone = $defaultTimeZone;
  }
  
  /**
   * Application's default view title
   * @return string
   */
  public function getDefaultViewTitle() {
    return $this->defaultViewTitle;
  }

  /**
   * Defines the application's default view title
   * @param string $defaultViewTitle
   * @return void
   */
  public function setDefaultViewTitle($defaultViewTitle) {
    $this->defaultViewTitle = $defaultViewTitle;
  }
    
  /**
   * Create application's default files and folders
   * @return boolean TRUE if no problems occur during installation and FALSE
   * otherwise
   */
  public function createApp($reinstall = false) {
    if(!isset($this->devEnvironment) || !isset($this->devDbInfo)){
      throw new main\ExtendedException('You need to set, at least, the development environment.', main\ExtendedException::E_WARNING);
    }
    file\FileHandler::recursiveCopy('../app-skeleton', $this->getAppRoot(), $reinstall);
    $initFilePath = $this->getAppRoot() . '/app/configs/init.php';
    file\FileHandler::createFile($initFilePath, $this->getInitContent(), $reinstall);
    return true;
  }

  /**
   * Generates the content to app/configs/init.php. Application's main
   * configuration file
   * @return string
   */
  public function getInitContent() {

    $initFile = <<<FILE
<?php
/**
 * Codeminus Framework Initializer
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */

/**
 * Development environment directory
 * @var string path relative to domain root directory
 */
const DEV_ENVIRONMENT = '{$this->getDevEnvironment()}';

/**
 * Production environment directory
 * @var string path relative to domain root directory
 */
const PRO_ENVIRONMENT = '{$this->getProEnvironment()}';

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
const VIEW_DEFAULT_TITLE = '{$this->getDefaultViewTitle()}';

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
const DEV_DB_HOST = '{$this->getDevDbInfo()['host']}';
const DEV_DB_USER = '{$this->getDevDbInfo()['user']}';
const DEV_DB_PASS = '{$this->getDevDbInfo()['pass']}';
const DEV_DB_NAME = '{$this->getDevDbInfo()['name']}';

//Production Database configuration
const PRO_DB_HOST = '{$this->getProDbInfo()['host']}';
const PRO_DB_USER = '{$this->getProDbInfo()['user']}';
const PRO_DB_PASS = '{$this->getProDbInfo()['pass']}';
const PRO_DB_NAME = '{$this->getProDbInfo()['name']}';

/**
 * Default timezone
 * @var string timezone  supported by php
 * @see http://www.php.net/manual/en/timezones.php for a list of supported timezones
 */
const DEFAULT_TIMEZONE = '{$this->getDefaultTimeZone()}';

/**
 * Default records per page on a result listing
 * @var int records per page
 */
const DEFAULT_RPP = 20;

/**
 * Default email address to be used as default sender on org\codeminus\util\Email
 * @var string email address
 */
const DEFAULT_EMAIL_SENDER_ADDRESS = '';

/**
 * Default email owner's name to be used as default sender on org\codeminus\util\Email
 * @var string email owner's name
 */
const DEFAULT_EMAIL_SENDER_NAME = '';

/**
 * Call the init function and set the application environment
 * Change the parameter according to environment
 */
init('dev');


###############################################################################
# DO NOT CHANGE BELOW THIS LINE
###############################################################################

/**
 * Initializes the environment constants
 * @param string \$mode One of the following environment modes:<br/>
 * 'dev' - Development environment<br/>
 * 'pro' - Production environment
 * @return void
 */
function init(\$mode = 'dev'){    
    
    switch (\$mode){

        case 'dev':
            \$environment = DEV_ENVIRONMENT;
            break;

        case 'pro':
            \$environment = PRO_ENVIRONMENT;
            break;

        default :
            exit('<b>Fatal error:</b> Invalid environment mode. Please specify a valid one.');
    }

    define('ENVIRONMENT_MODE', \$mode);

    date_default_timezone_set(DEFAULT_TIMEZONE);
    
    \$path = \$_SERVER['DOCUMENT_ROOT'] . \$environment;

    if(!is_dir(\$path)){
        exit('<b>Fatal error:</b> Invalid environment directory. Path: ' . \$path . ' does not exist.');
    }
    
    define('APP_ENVIRONMENT_PATH',\$environment);
    define('APP_PATH', \$path);    
    define('APP_HTTP_PATH', 'http://' . \$_SERVER['HTTP_HOST'] . \$environment);
    
    define('CONTROLLER_PATH', APP_PATH . CONTROLLER_DIR);
    define('VIEW_PATH', APP_PATH . VIEW_DIR);
    
    define('CMF_ASSETS', \$environment . '/org/codeminus/assets');
    define('CMF_CSS_PATH', \$environment . '/org/codeminus/css');
    define('CMF_JS_PATH', \$environment . '/org/codeminus/js');
    
    switch (ENVIRONMENT_MODE){

        case 'dev':

            error_reporting(E_ALL);
            define('DB_HOST',  DEV_DB_HOST);
            define('DB_USER',  DEV_DB_USER);
            define('DB_PASS',  DEV_DB_PASS);
            define('DB_NAME',  DEV_DB_NAME);

            break;

        case 'pro':

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
    require_once APP_PATH . '/' .  str_replace('\\\', '/', \$className) . '.php';
});


use \org\codeminus\main as main;

//Initializing path router
try{
    new main\Router();
}  catch (main\ExtendedException \$e){
    echo \$e->getMessage();
    exit;
}
FILE;

    return $initFile;
  }

}