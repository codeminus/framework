<?php

namespace codeminus\file;

use codeminus\main as main;

/**
 * Configuration file handler
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class Ini {

  private $path;
  private $iniArray;
  private $keyPrefix;
  
  /**
   * Handles ini configuration files
   * @param string $path The path to the ini file
   * @param bool $processSections  if TRUE it will handle the file as a
   * multidimensional array, organizing directives into sections
   * @return Ini
   */
  public function __construct($path = null, $processSections = false) {
    if (isset($path)) {
      if (is_file($path)) {
        $this->setPath($path);
        $this->setArray(parse_ini_file($path, $processSections));
      }else{
        throw new main\ExtendedException("$path is not a valid file", main\ExtendedException::E_ERROR);
      }
    } else {
      $this->setArray(array());
    }
  }

  /**
   * Ini file path
   * @return string The path to the ini file
   */
  public function getPath() {
    return $this->path;
  }

  /**
   * Ini file path
   * @param string $path The path to the ini file
   * @return void
   */
  public function setPath($path) {
    $this->path = $path;
  }

  /**
   * Ini as array
   * @return array Returns the ini file as an array
   */
  public function getArray() {
    return $this->iniArray;
  }

  /**
   * Ini as string
   * @return string Returns the ini file as a string
   */
  public function getString() {
    $str = '';
    foreach ($this->iniArray as $section => $directive) {
      if (is_array($directive)) {
        $str .= "[$section]" . PHP_EOL;
        foreach ($directive as $key => $value) {
          $str .= "$key = \"$value\"" . PHP_EOL;
        }
      } else {
        $str .= "$section = \"$directive\"" . PHP_EOL;
      }
    }
    return $str;
  }

  /**
   * Stores the ini file content as an array
   * @param array $iniArray the ini content
   * @return void
   */
  private function setArray($iniArray) {
    $this->iniArray = $iniArray;
  }
  
  /**
   * A key prefix to work as a shortcut
   * @return string
   */
  public function getKeyPrefix() {
    return $this->keyPrefix;
  }

  /**
   * A key prefix to work as a shortcut
   * @param string $keyPrefix The string to be prepended to every key request 
   * using get()
   */
  public function setKeyPrefix($keyPrefix) {
    $this->keyPrefix = $keyPrefix;
  }

      
  /**
   * Ini directives
   * @param string $key The directive key
   * @param string $section If $section is given, it will search for the $key
   * inside it
   * @return string Returns the value for the requested $key
   */
  public function get($key, $section = null) {
    if (isset($section)) {
      if(isset($this->iniArray[$section][$this->getKeyPrefix() . $key])){
        return $this->iniArray[$section][$this->getKeyPrefix() . $key];
      }else{
        throw new main\ExtendedException("[$section][{$this->getKeyPrefix()} . $key] does not exists on ini file", main\ExtendedException::E_ERROR);
      }
      
    } else {
      return $this->iniArray[$this->getKeyPrefix() . $key];
    }
  }

  /**
   * Ini directives
   * @param string $key The directive key
   * @param string $value The directive value
   * @param string $section The directive section
   * @return void
   */
  public function set($key, $value, $section = null) {
    $this->iniArray[$section][$key] = $value;
  }

  /**
   * Saves the ini file
   * @param string $path The file path
   * @return bool TRUE if the ini file was created with success or FALSE
   * otherwise
   * @throws main\ExtendedException
   */
  public function save($path = null) {
    if (!isset($path)) {
      if (isset($this->path)) {
        return File::create($this->path, $this->getString(), true);
      } else {
        throw new main\ExtendedException('no file set', main\ExtendedException::E_ERROR);
      }
    } else {
      return File::create($path, $this->getString(), true);
    }
  }

}