<?php

namespace codeminus\file;

use codeminus\util as util;

/**
 * File
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.2
 */
class File {

  private function __construct() {
    //prevents class instantiation
  }
  
  /**
   * Deletes a file or directory
   * Alias for codeminus\file\Directory::delete()
   * @see codeminus\file\Directory::delete() for more information
   * @param string $path
   * @param boolean $recursively
   * @return boolean
   */
  public static function delete($path, $recursively = false){
    return Directory::delete($path, $recursively);
  }

  /**
   * Creates a file
   * @param string $filePath the file path
   * @param string $fileContent Content to be put inside the file
   * method
   * @param boolean $replaceExistent[optional] if TRUE it will replace all
   * existent files
   * @return boolean TRUE if the file was created with success and FALSE 
   * otherwise
   */
  public static function create($filePath, $fileContent, $replaceExistent = false) {
    if ($replaceExistent) {
      Directory::delete($filePath);
    }
    if (!file_exists($filePath)) {
      if (!file_put_contents($filePath, $fileContent)) {
        return false;
      } else {
        util\ClassLog::add(__METHOD__, $filePath . ' created');
        return true;
      }
    } else {
      util\ClassLog::add(__METHOD__, $filePath . ' not created. File already exists', util\ClassLog::LOG_WARNING);
      return false;
    }
  }

  /**
   * Returns the extension of a given file
   * @param string $fileName the file name
   * @return string extension the file extension
   */
  public static function getExtension($fileName) {
    $parts = explode(".", $fileName);
    return strtolower(end($parts));
  }

  /**
   * Validate file extension
   * @param string $fileName the file name
   * @param string $validExtensions List of extensions separated by ,(comma).
   * <br/>Example: "gif, jpg, png"
   * @return boolean TRUE if the extension is valid and FALSE otherwise.
   */
  public static function validateExtension($fileName, $validExtensions) {
    $fileExtension = self::getExtension($fileName);
    $validExtensions = explode(',', $validExtensions);
    foreach ($validExtensions as $ext) {
      if ($fileExtension == trim($ext)) {
        return true;
      }
    }
    return false;
  }

}