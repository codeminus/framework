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

  public static function init() {
    spl_autoload_register();
    $cmfPath = preg_replace('/\\\/','/',substr(__FILE__, 0, strpos(__FILE__, '\codeminus')));
    self::includePath($cmfPath);
  }

  public static function getIncludePath(){
    return explode(PATH_SEPARATOR, get_include_path());
  }
  
  public static function includePath($path) {
    if(!array_search($path, self::getIncludePath())){
      set_include_path(get_include_path() . PATH_SEPARATOR . $path);
    }
  }
}
