<?php

namespace codeminus\file;

use codeminus\util as util;

/**
 * Directory
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class Directory {
  
  const MATCH_ANY = 0;
  const MATCH_BEGINNING = 1;
  const MATCH_END = 2;
  const MATCH_ALL = 3;
  
  /**
   * Creates a directory
   * @param string $dir path and name
   * @param octal $mode[optional] the access level desired for the folder
   * @return boolean TRUE if the directory was created with success and FALSE
   * otherwise
   */
  public static function create($dir, $mode = 0755) {
    if (!file_exists($dir)) {
      if (!mkdir($dir, $mode, true)) {
        return false;
      } else {
        util\ClassLog::add(__METHOD__, $dir . ' created');
        return true;
      }
    } else {
      util\ClassLog::add(__METHOD__, $dir . ' not created. Directory already exists', util\ClassLog::LOG_WARNING);
      return false;
    }
  }
  
  /**
   * Verifies if the a given directory is empty
   * @param string $directory directory path
   * @return boolean TRUE is the directory is empty and FALSE otherwise
   */
  public static function isEmpty($directory) {
    $isEmpty = true;
    $dirHandler = dir($directory);
    while (($file = $dirHandler->read()) !== false) {
      if ($file != '.' && $file != '..') {
        $isEmpty = false;
        break;
      }
    }
    return $isEmpty;
  }
  
  /**
   * Deletes a file or directory
   * @param string $path path to the file or directory to be deleted
   * @param boolean $recursively if TRUE it will delete all subdirectories
   * @return boolean TRUE if the directory was deleted with success and FALSE
   * otherwise
   */
  public static function delete($path, $recursively = false) {
    if (is_dir($path)) {
      if (self::isEmpty($path)) {
        rmdir($path);
        util\ClassLog::add(__METHOD__, $path . ' deleted');
      } elseif ($recursively) {
        $dirHandler = dir($path);
        while (($file = $dirHandler->read()) !== false) {
          if ($file != '.' && $file != '..') {
            $filePath = $path . DIRECTORY_SEPARATOR . $file;
            if (is_dir($filePath)) {
              self::delete($filePath, $recursively);
            } else {
              unlink($filePath);
              util\ClassLog::add(__METHOD__, $filePath . ' deleted');
            }
          }
        }
        if (self::isEmpty($path)) {
          rmdir($path);
          util\ClassLog::add(__METHOD__, $path . ' deleted');
        }
      } else {
        util\ClassLog::add(__METHOD__, $path . ' not deleted. The directory is not empty', util\ClassLog::LOG_ERROR);
        return false;
      }
    } else {
      unlink($path);
      util\ClassLog::add(__METHOD__, $path . ' deleted');
    }
    return true;
  }
  
  /**
   * Searchs for files and directories within a given directory
   * @param string $what the expression to search for
   * @param string $where the directory to search in
   * @param array $storage the array that is going to store the result of the
   * search
   * @param int $matchMode[optional] One of the Directory class match constants.
   * If MATCH_ANY is given, it will match any part of the name of the directory
   * or file. If MATCH_BEGINNING is given, it will match only the beginning. If
   * MATCH_END is given, it will match only the end. If MATCH_ALL is given, it
   * will match the whole name of the directory or file
   * @param boolean $recursively[optional] if TRUE is given, will search on all
   * subdirectories
   * @param boolean $caseSensitive[optional] if TRUE is given, the search will
   * be case sensitive.
   * @param boolean $ignoreExtension[optional] if TRUE is given, the search will
   * consider only the file name and not its extension
   * @return boolean TRUE if any match is found
   */
  public static function find($what, $where, &$storage, $matchMode = self::MATCH_ANY, $recursively = false, $caseSensitive = false, $ignoreExtension = false) {
    if (!$caseSensitive) {
      $what = strtolower($what);
    }
    $dirHandler = dir($where);
    while (($file = $dirHandler->read()) !== false) {
      if ($file != '.' && $file != '..') {
        $filePath = $where . DIRECTORY_SEPARATOR . $file;
        if (!$caseSensitive) {
          $file = strtolower($file);
        }
        if (!is_dir($filePath)) {
          if ($ignoreExtension) {
            $file = str_replace('.' . File::getExtension($file), '', $file);
          }
        }
        $found = false;
        switch ($matchMode) {
          case self::MATCH_ANY :
            if (strpos($file, $what) > -1) {
              $found = true;
            }
            break;
          case self::MATCH_BEGIN :
            if (strpos($file, $what) === 0) {
              $found = true;
            }
            break;
          case self::MATCH_END :
            $whatCharCount = strlen($what);
            $fileCharCount = strlen($file);
            $offset = $fileCharCount - $whatCharCount;
            if ($offset > -1) {
              if (strpos($file, $what, $offset) > -1) {
                $found = true;
              }
            }
            break;
          case self::MATCH_ALL :
            if ($file === $what) {
              $found = true;
            }
            break;
        }
        if ($found) {
          $storage[] = $filePath;
        }
        if (is_dir($filePath) && $recursively) {
          self::find($what, $filePath, $storage, $matchMode, $recursively, $caseSensitive, $ignoreExtension);
        }
      }
    }
    return !empty($storage);
  }
  
  /**
   * Scans all files and subdirectories of a given directory
   * @param string $directory path
   * @return array an array containing numeric keys to store file paths and
   * and associative keys to store directory paths. Example:<br/>
   * print_r(Directory::recursiveScan('../css'));<br/>
   * [0] => base.css<br/>
   * ...<br/>
   * [6] => famfamfam.css<br/>
   * [7] => forms.css<br/>
   * ...<br/>
   * [icon] => Array<br/>
   *  (<br/>
   *    [0] => famfamfam.png<br/>
   *    [1] => glyphicons.png<br/>
   *  )<br/>
   * ...<br/>
   */
  public static function recursiveScan($directory) {
    $tree = array();
    $dirHandler = dir($directory);
    while (($subdir = $dirHandler->read()) !== false) {
      if ($subdir != '.' && $subdir != '..') {
        if (is_dir($directory . DIRECTORY_SEPARATOR . $subdir)) {
          $tree[$subdir] = self::recursiveScan($directory . DIRECTORY_SEPARATOR . $subdir);
        } else {
          $tree[] = $subdir;
        }
      }
    }
    return $tree;
  }
  
  /**
   * Copies all files from a folder to another recursively
   * @param string $source source path to copy from
   * @param string $destination destination path to copy to
   * @param boolean $replaceExistent[optional] if TRUE it will replace all
   * existent files
   * @return void
   */
  public static function recursiveCopy($source, $destination, $replaceExistent = false) {
    $dirHandler = dir($source);
    if (!file_exists($destination)) {
      mkdir($destination);
      util\ClassLog::add(__METHOD__, $destination . ' created');
    } else {
      util\ClassLog::add(__METHOD__, $destination . ' not created. Directory already exists', util\ClassLog::LOG_WARNING);
    }
    while (($file = $dirHandler->read()) !== false) {
      if ($file != '.' && $file != '..') {
        $srcFilePath = $source . DIRECTORY_SEPARATOR . $file;
        $dstFilePath = $destination . DIRECTORY_SEPARATOR . $file;
        if (is_dir($srcFilePath)) {
          self::recursiveCopy($srcFilePath, $dstFilePath, $replaceExistent);
        } else {
          if (file_exists($dstFilePath) && $replaceExistent) {
            self::delete($dstFilePath);
          }
          if (!file_exists($dstFilePath)) {
            copy($srcFilePath, $dstFilePath);
            util\ClassLog::add(__METHOD__, $dstFilePath . ' created');
          } else {
            util\ClassLog::add(__METHOD__, $dstFilePath . ' not created. File already exists', util\ClassLog::LOG_WARNING);
          }
        }
      }
    }
  }
  
}