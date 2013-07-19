<?php

namespace codeminus\main;

/**
 * Autoloader class
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.1
 */
class Autoloader {
  
  private function __construct(){
    //prevent instance
  }
  
  private function __clone(){
    //prevent clone
  }

  /**
   * Initializes the autoloader
   * @return void
   */
  public static function init() {
    spl_autoload_register();
    $cmfPath = preg_replace('/\\\/','/',substr(__FILE__, 0, strpos(__FILE__, '\codeminus')));
    self::includePath($cmfPath);
  }

  /**
   * The php include path
   * @return array An array containing all include paths
   */
  public static function getIncludePath(){
    return explode(PATH_SEPARATOR, get_include_path());
  }
  
  /**
   * Add paths to the php include path
   * @param string $path
   * @return void
   */
  public static function includePath($path) {
    if(!array_search($path, self::getIncludePath())){
      set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    }
  }
}
