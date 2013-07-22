<?php

namespace codeminus\traits;

use codeminus\main as main;

/**
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
trait MagicClass {

  /**
   * This magic method is trigged when invoking inaccessible methods
   * @param string $method
   * @param array $args
   * @return mixed
   * @throws main\ExtendedException
   */
  public function __call($method, $args) {
    if (strpos($method, 'get') === 0) {
      $property = str_replace('get', '', $method);
      if (!property_exists($this, $property)) {
        $property = lcfirst($property);
        if (!property_exists($this, $property)) {
          throw new main\ExtendedException("$property is not a " . __CLASS__
          . " property", E_ERROR);
        }
      }
      return $this->$property;
    } elseif (strpos($method, 'set') === 0) {
      $property = str_replace('set', '', $method);
      if (!property_exists($this, $property)) {
        $property = lcfirst($property);
        if (!property_exists($this, $property)) {
          throw new main\ExtendedException("$property is not a " . __CLASS__
          . " property", E_ERROR);
        }
      }
      $this->$property = $args[0];
    }
  }

}