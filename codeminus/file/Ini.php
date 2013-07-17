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
  private $defaultSection;
  private $keyPrefix;
  
  /**
   * Handles ini configuration files
   * @param string $path[optional] The path to the ini file. If null is given
   * it will create a new one
   * @param bool $processSections[optional]  if TRUE it will handle the file as a
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
   * The section to be used by default when null is given for $section parameter
   * on get() and set() methods
   * @return string
   */
  public function getDefaultSection() {
    return $this->defaultSection;
  }

  /**
   * The section to be used by default when null is given for $section parameter
   * on get() and set() methods
   * @param string $defaultSection
   * return void
   */
  public function setDefaultSection($defaultSection) {
    $this->defaultSection = $defaultSection;
  }
    
  /**
   * The value that is always prepended to a key before setting or getting it
   * @return string
   */
  public function getKeyPrefix() {
    return $this->keyPrefix;
  }

  /**
   * The value that is always prepended to a key before setting or getting it
   * @param string $keyPrefix The string to be prepended to every key request
   */
  public function setKeyPrefix($keyPrefix) {
    $this->keyPrefix = $keyPrefix;
  }

      
  /**
   * Ini directives
   * @param string $key The directive key
   * @param string $section[optional] If $section is given, it will search for the $key
   * inside it
   * @return string Returns the value for the requested $key
   */
  public function get($key, $section = null) {
    if(!isset($section)){
      $section = $this->getDefaultSection();
    }
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
   * @param string $section[optional] The directive section
   * @return void
   */
  public function set($key, $value, $section = null) {
    if(!isset($section)){
      $section = $this->getDefaultSection();
    }
    if(isset($section)){
      $this->iniArray[$section][$this->getKeyPrefix() . $key] = $value;
    }else{
      $this->iniArray[$this->getKeyPrefix() . $key] = $value;
    }
    
  }

  /**
   * Saves the ini file
   * @param string $path[optional] The file path. If null if given it will try
   * to replace original file
   * @param bool $replace[optional] if TRUE, it will replace the existent file
   * @return bool TRUE if the ini file was created with success or FALSE
   * otherwise
   * @throws main\ExtendedException
   */
  public function save($path = null, $replace = true) {
    if (!isset($path)) {
      if (isset($this->path)) {
        return File::create($this->path, $this->getString(), $replace);
      } else {
        throw new main\ExtendedException('no file set', main\ExtendedException::E_ERROR);
      }
    } else {
      return File::create($path, $this->getString(), $replace);
    }
  }

}