<?php

namespace org\codeminus\main;

/**
 * Handles the URL query string
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0.1
 */
class Router {

  /**
   * Query string array
   * @var array 
   */
  private $queryStringArray;

  /**
   * The instance of the requested controller
   * @var object 
   */
  private $controllerInstance;

  /**
   * Requested controller's name
   * @var string
   */
  public static $CONTROLLER_NAME;

  /**
   * Requested controller's method name
   * @var string
   */
  public static $CONTROLLER_METHOD_NAME;

  /**
   * Requested controller's method arguments
   * @var array 
   */
  public static $CONTROLLER_METHOD_ARGS = array();

  /**
   * Query string variables
   * @var string
   * @example from http://example.com/Controller/?myvar=123&myOtherVar=456
   * $QUERY_STRING_VARS would store ?myvar=123&myOtherVar=456
   */
  public static $QUERY_STRING_VARS = '?';

  /**
   * Handles the URL query string
   * @return Router
   * @throws ExtendedException
   */
  public function __construct() {
    if (APP_ENVIRONMENT_PATH != '') {
      $queryString = str_replace(APP_ENVIRONMENT_PATH . '/', '', $_SERVER['REQUEST_URI']);
    } else {
      $queryString = trim($_SERVER['REQUEST_URI'], '/');
    }

    $this->setQueryStringVars($queryString);
    
    //if there's a query string and it is not $_GET values
    if ($queryString != "" && substr($queryString, 0, 1) != '?') {
      #$queryString = rtrim($queryString, '/');            
      $this->setQueryStringArray(explode('/', $queryString));

      //if the requested controller exists
      if ($this->requireController(self::$CONTROLLER_NAME)) {
        $this->setControllerInstance(self::$CONTROLLER_NAME);

        //if the requested controller doesnt exist but the error controller exists
      } else if ($this->requireController(ERROR_CONTROLLER)) {
        self::$CONTROLLER_NAME = ERROR_CONTROLLER;
        $this->setControllerInstance(ERROR_CONTROLLER);

        //if neither controller is found    
      } else {
        
        throw new ExtendedException('Neither <b>' . self::$CONTROLLER_NAME . '</b> or <b>' . ERROR_CONTROLLER . '</b> were found in <b>' . CONTROLLER_PATH . '</b>', ExtendedException::E_ERROR);
      }

      //if no controller is requested try to load INDEX_CONTROLLER
    } else if ($this->requireController(INDEX_CONTROLLER)) {
      self::$CONTROLLER_NAME = INDEX_CONTROLLER;
      self::$CONTROLLER_METHOD_NAME = 'index';
      $this->setControllerInstance(INDEX_CONTROLLER);

      //if the INDEX_CONTROLLER doesnt exists
    } else {
      throw new ExtendedException('Default controller ' . INDEX_CONTROLLER . ' not found in ' . CONTROLLER_PATH, ExtendedException::E_ERROR);
    }
  }

  /**
   * Query string array
   * @return array
   */
  public function getQueryStrings() {
    return $this->queryStringArray;
  }

  /**
   * Stores Query string array and populates static properties
   * @param string $queryStringArray
   * @return void
   */
  private function setQueryStringArray($queryStringArray) {
    $this->queryStringArray = $queryStringArray;
    self::$CONTROLLER_NAME = $queryStringArray[0];

    //if there's something defined in the query string besides the controller
    if (isset($this->queryStringArray[1])) {
      //if it's not $_GET values
      if (strpos($this->queryStringArray[1], '?') === false) {
        self::$CONTROLLER_METHOD_NAME = $queryStringArray[1];
        self::$CONTROLLER_METHOD_ARGS = array_slice($this->getQueryStrings(), 2, null, false);
      }
    }
  }

  /**
   * Query string variables
   * @return string
   */
  public function getQueryStringVars() {
    return self::$QUERY_STRING_VARS;
  }

  /**
   * Extracts the query string variables from the URI and stores it in self::$QUERY_STRING_VARS
   * @param type $queryString
   * @return void
   */
  private function setQueryStringVars($queryString) {
    if (strpos($queryString, '/?') > -1) {
      self::$QUERY_STRING_VARS = substr($queryString, strpos($queryString, '/?') + 1);
    }
  }

  /**
   * Requires the controller class file
   * @param string $controller
   * @return void
   */
  private function requireController($controller) {
    $path = CONTROLLER_PATH . '/' . $controller . '.php';
    if (file_exists($path)) {
      require $path;
      return true;
    } else {
      return false;
    }
  }

  /**
   * Controller object instance
   * Instanciates the requested controller
   * @param string $ctrl
   * @return void
   */
  private function setControllerInstance($ctrl) {
    $this->controllerInstance = new $ctrl;
    $this->callControllerMethod();
  }

  /**
   * Controller object instance
   * @return object
   */
  private function getControllerInstance() {
    return $this->controllerInstance;
  }

  /**
   * Requested controller method's argument
   * @param int $index of the argument on $CONTROLLER_METHOD_ARGS array
   * @return mixed
   */
  public static function getMethodArg($index) {
    return self::$CONTROLLER_METHOD_ARGS[$index];
  }

  /**
   * Invokes the requested method
   * @return void
   */
  private function callControllerMethod() {
    //if there's a method to be called
    if (self::$CONTROLLER_METHOD_NAME != null) {
      //if the requested method exists within the given controller
      if (method_exists(self::$CONTROLLER_NAME, self::$CONTROLLER_METHOD_NAME)) {
        call_user_func_array(array($this->getControllerInstance(), self::$CONTROLLER_METHOD_NAME), self::$CONTROLLER_METHOD_ARGS);
        //redirect to controller's index method if the requested method doesnt exists
      } else {
        header('Location: ' . APP_HTTP_PATH . '/' . self::$CONTROLLER_NAME);
        exit;
      }

      //if no method was requested, calls the index method.
      //the index method must be implemented on all controllers as determined
      //by the abstract class org\codeminus\main\Controller 
    } else {
      $ctrlInstance = $this->getControllerInstance();
      $ctrlInstance->index();
    }
  }

}