<?php
namespace codeminus\db;

/**
 * Database record search utility class
 * @author Wilson Santos <wilson@codeminus.org> 
 * @version 0.1
 */
class Search {
  public static function clearKey($key){
    return preg_replace('/[\/\'"#]|--/', '', $key);
  }
}