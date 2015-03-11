<?php

namespace codeminus\traits;

/**
 * Singleton pattern implementation
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
trait Singleton {

  /**
   * Stores the class instance
   * @var object 
   */
  private static $instance;

  /**
   * Prevent class instance
   */
  private function __construct() {
    
  }

  /**
   * Prevent class instance clone
   */
  private function __clone() {
    
  }

  /**
   * Returns the single class instance
   * @return object
   */
  public function getInstance() {
    if (!isset(self::$instance)) {
      self::$instance = new self();
    }
    return self::$instance;
  }

}