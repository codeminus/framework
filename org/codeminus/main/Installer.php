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
  private $viewDefaultTitle;
  
  /**
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

  public function getAppRoot() {
    return $this->appRoot;
  }

  private function setAppRoot($appRoot) {
    $this->appRoot = $appRoot;
  }

  public function getDevEnvironment() {
    return $this->devEnvironment;
  }

  public function setDevEnvironment($devEnvironment) {
    $this->devEnvironment = $devEnvironment;
  }

  public function getProEnvironment() {
    return $this->proEnvironment;
  }

  public function setProEnvironment($proEnvironment) {
    $this->proEnvironment = $proEnvironment;
  }

  public function getDevDbInfo() {
    return $this->devDbInfo;
  }

  public function setDevDbInfo($host, $user, $pass, $database) {
    $this->devDbInfo['host'] = $host;
    $this->devDbInfo['user'] = $user;
    $this->devDbInfo['pass'] = $pass;
    $this->devDbInfo['name'] = $database;
  }

  public function getProDbInfo() {
    return $this->proDbInfo;
  }

  public function setProDbInfo($host, $user, $pass, $database) {
    $this->proDbInfo['host'] = $host;
    $this->proDbInfo['user'] = $user;
    $this->proDbInfo['pass'] = $pass;
    $this->proDbInfo['name'] = $database;
  }

  public function getDefaultTimeZone() {
    return $this->defaultTimeZone;
  }

  public function setDefaultTimeZone($defaultTimeZone) {
    $this->defaultTimeZone = $defaultTimeZone;
  }
    
  public function getViewDefaultTitle() {
    return $this->viewDefaultTitle;
  }

  public function setViewDefaultTitle($viewDefaultTitle) {
    $this->viewDefaultTitle = $viewDefaultTitle;
  }

    
  /**
   * Create application's default files and folders
   * @return boolean
   */
  public function createApp() {
    if(!isset($this->devEnvironment) || !isset($this->devDbInfo)){
      throw new main\ExtException('You need to set, at least, the development environment.');
    }
    file\FileHandler::recursiveCopy('../app-skeleton', $this->getAppRoot());
    $initFilePath = $this->getAppRoot() . '/app/configs/init.php';
    file\FileHandler::createFile($initFilePath, $this->getInitContent());
    return true;
  }

  /**
   * Returns the Application's environment assuming that the framework package
   * has the same root folder as the application
   * @return string
   */
  public static function getInvironment() {
    return substr($_SERVER['SCRIPT_NAME'], 0, strpos($_SERVER['SCRIPT_NAME'], '/org'));
  }

  /**
   * Application HTTP path
   * @return string
   */
  public function getAppHttpPath() {
    return 'http://' . $_SERVER['HTTP_HOST'] . $this->getInvironment();
  }

  /**
   * Generates the content to the app/configs/init.php main configuration file
   * @return string
   */
  public function getInitContent() {

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
const VIEW_DEFAULT_TITLE = '{$this->getViewDefaultTitle()}';

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