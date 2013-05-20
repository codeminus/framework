<?php

namespace org\codeminus\main;

/**
 * Base controller
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
abstract class Controller {

  /**
   * View object
   * @var View
   */
  protected $view;
  
  /**
   * The name of the last controller called
   * @var string
   */
  static $LASTCALLED;
  

  /**
   * Base controller
   * @return Controller
   */
  public function __construct() {
    $this->view = new View();
    self::$LASTCALLED = get_called_class();
  }
  
  /**
   * Redirects to a given location relative to APP_HTTP_PATH
   * @param string $url
   * @return void
   */
  public static function redirectTo($url) {
    header('Location: ' . APP_HTTP_PATH . '/' . $url);
    exit;
  }

  /**
   * Prepends APP_HTTP_PATH . '/' to the given query
   * @param string $query you want to invoke.
   * ex.: TestController/testMethod/arg1/arg2
   * @return string link source
   */
  public static function linkTo($query) {
    return APP_HTTP_PATH . '/' . $query;
  }

  /**
   * Default method to be called when none is given on query string
   * @return void
   */
  abstract public function index();
}