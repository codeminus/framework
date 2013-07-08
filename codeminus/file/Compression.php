<?php

namespace codeminus\file;

use codeminus\main as main;

/**
 * Compression
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class Compression {

  private static $parentPath;

  private function __construct() {
    //prevent instantiation
  }

  /**
   * Populates a zip file recursively
   * @param string $path the directory to add to the zip file
   * @param \ZipArchive $zip the zip file that will be populated
   * @return void
   */
  private static function populateZip($path, \ZipArchive &$zip) {
    if (!isset(self::$parentPath)) {
      self::$parentPath = dirname($path) . DIRECTORY_SEPARATOR;
    }
    $dirHandler = dir($path);
    while (($file = $dirHandler->read()) !== false) {
      if ($file != '.' && $file != '..') {
        $fileParh = $path . DIRECTORY_SEPARATOR . $file;
        $zipPath = substr($fileParh, strlen(self::$parentPath));
        if (is_dir($fileParh)) {
          $zip->addEmptyDir($zipPath);
          self::populateZip($fileParh, $zip);
        } else {
          $zip->addFile($fileParh, $zipPath);
        }
      }
    }
  }

  /**
   * Creates a zip file
   * @param string $path The file of directory to add to the zip file
   * @param string $destinationFile[optional] the path to the zip file
   * @param boolean $overwrite[optional] If TRUE, it will overwrite the existent zip file
   * @return mixed Returns a string with the status message on success or false
   * on failure
   * @throws main\ExtendedException
   */
  public static function zip($path, $destinationFile = null, $overwrite = true) {
    if (!isset($destinationFile)) {
      $destinationFile = $path . '.zip';
    }

    $zip = new \ZipArchive();
    if ($overwrite) {
      $zip->open($destinationFile, \ZipArchive::OVERWRITE);
    } else {
      $zip->open($destinationFile, \ZipArchive::CREATE);
    }

    if (is_dir($path)) {
      $zip->addEmptyDir(basename($path));
      self::populateZip($path, $zip);
    } else {
      $zip->addFile($path);
    }
    $status = $zip->getStatusString();
    $zip->close();
    if (!is_file($destinationFile)) {
      throw new main\ExtendedException($destinationFile . ' is not a file');
    }
    return $status;
  }

  /**
   * Extract a zip file to a given destination
   * @param string $zipFile the zip file to be extracted
   * @param string $destination[optional] Where to extract the zip file
   * @return boolean TRUE if the extraction was successful and FALSE otherwise
   * @throws main\ExtendedException
   */
  public static function extractZip($zipFile, $destination = null) {
    if(!isset($destination)){
      $destination = dirname($zipFile);
    }
    if (is_dir($destination)) {
      $zip = new \ZipArchive();
      if ($zip->open($zipFile)) {
        return $zip->extractTo($destination);
      } else {
        throw new main\ExtendedException('Unable to open ' . $zipFile);
      }
    } else {
      throw new main\ExtendedException($destination . ' is not a valid directory');
    }
  }

}