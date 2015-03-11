<?php

namespace codeminus\main;

/**
 * Autoloader class
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.6
 */
class Autoloader {

  private function __construct() {
    //prevent instance
  }

  private function __clone() {
    //prevent clone
  }

  /**
   * Initializes the autoloader
   * @return void
   */
  public static function init() {
    $cmfPath = preg_replace('/\\\/', '/', substr(__FILE__, 0, strpos(__FILE__, DIRECTORY_SEPARATOR . 'codeminus')));
    self::includePath($cmfPath);
    spl_autoload_register(function($className) {
      foreach (self::getIncludePath() as $path) {
        $classPath = $path . DIRECTORY_SEPARATOR . str_replace('\\', '/', $className) . '.php';
        if (is_readable($classPath)) {
          require_once $classPath;
        }
      }
    });
  }

  /**
   * The php include path
   * @return array An array containing all include paths
   */
  public static function getIncludePath() {
    return explode(PATH_SEPARATOR, get_include_path());
  }

  /**
   * Add paths to the php include path
   * @param string $path
   * @return void
   */
  public static function includePath($path) {
    if (!array_search($path, self::getIncludePath())) {
      set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    }
  }

}
