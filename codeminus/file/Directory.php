<?php

namespace codeminus\file;

use codeminus\main as main;
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
  const MATCH_PCRE = 4;

  private function __construct() {
    //prevents class instantiation
  }

  /**
   * Creates a directory
   * @param string $directory path If a subdirectory is given and it does not
   * exists it will be created as well
   * @param octal $mode[optional] the access level desired for the folder
   * @return boolean TRUE if the directory was created with success and FALSE
   * otherwise
   */
  public static function create($directory, $mode = 0755) {
    if (!file_exists($directory)) {
      if (!mkdir($directory, $mode, true)) {
        return false;
      } else {
        util\ClassLog::add(__METHOD__, $directory . ' created');
        return true;
      }
    } else {
      util\ClassLog::add(__METHOD__, $directory . ' not created. Directory already exists', util\ClassLog::LOG_WARNING);
      return false;
    }
  }

  /**
   * Verifies if a given directory is empty
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
   * @throws codeminus\main\ExtendedException
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
      if (!file_exists($path)) {
        throw new main\ExtendedException('Unable to find ' . $path);
      }
      unlink($path);
      util\ClassLog::add(__METHOD__, $path . ' deleted');
    }
    return true;
  }

  /**
   * Searchs for files and directories within a given directory
   * @param string $expression the expression to search for
   * @param string $where the directory to search in
   * @param array $storage the array that is going to store the result of the
   * search
   * @param int $matchMode[optional] One of the Directory class match constants.
   * If MATCH_ANY is given, it will match any part of the name of the directory
   * or file. If MATCH_BEGINNING is given, it will match only the beginning. If
   * MATCH_END is given, it will match only the end. If MATCH_ALL is given, it
   * will match the whole name of the directory or file
   * @param boolean $recursively[optional] if TRUE is given, it will search on
   * all subdirectories
   * @param boolean $caseSensitive[optional] if TRUE is given, the search will
   * be case sensitive.
   * @param boolean $ignoreExtension[optional] if TRUE is given, the search will
   * consider only the file name and not its extension
   * @return boolean TRUE if any match is found
   */
  public static function find($expression, $where, &$storage, $matchMode = self::MATCH_ANY, $recursively = false, $caseSensitive = false, $ignoreExtension = false) {
    if (!$caseSensitive) {
      $expression = strtolower($expression);
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
            if (strpos($file, $expression) > -1) {
              $found = true;
            }
            break;
          case self::MATCH_BEGINNING :
            if (strpos($file, $expression) === 0) {
              $found = true;
            }
            break;
          case self::MATCH_END :
            $expressionCharCount = strlen($expression);
            $fileCharCount = strlen($file);
            $offset = $fileCharCount - $expressionCharCount;
            if ($offset > -1) {
              if (strpos($file, $expression, $offset) > -1) {
                $found = true;
              }
            }
            break;
          case self::MATCH_ALL :
            if ($file === $expression) {
              $found = true;
            }
            break;
          case self::MATCH_PCRE :
            if (preg_match($expression, $file)) {
              $found = true;
            }
            break;
        }
        if ($found) {
          $storage[] = $filePath;
        }
        if (is_dir($filePath) && $recursively) {
          self::find($expression, $filePath, $storage, $matchMode, $recursively, $caseSensitive, $ignoreExtension);
        }
      }
    }
    return !empty($storage);
  }

  /**
   * Search for files whose contents matches the given expression
   * @param string $expression the expression to search for. PCRE supported
   * @param string $where the directory to search in
   * @param array $storage the array that is going to store the result of the
   * search
   * @param boolean $recursively[optional] if TRUE is given, it will search on
   * all subdirectories
   * @return boolean TRUE if any match is found
   */
  public static function findWithinFile($expression, $where, &$storage, $recursively = false) {
    $dirHandler = dir($where);
    while (($file = $dirHandler->read()) !== false) {
      if ($file != '.' && $file != '..') {
        $filePath = $where . DIRECTORY_SEPARATOR . $file;
        if (is_dir($filePath) && $recursively) {
          self::findWithinFile($expression, $filePath, $storage, $recursively);
        }elseif(is_file($filePath)){
          $filePathContent = file_get_contents($filePath);
          if(preg_match($expression, $filePathContent)){
            $storage[] = $filePath;
          }
        }
      }
    }
    return !empty($storage);
  }

  /**
   * Scans all files and folders within a given directory
   * @param string $directory path
   * @param boolean $recursively If TRUE is given, it will scan all
   * subdirectories as well
   * @return array an array containing numeric keys to store file paths and
   * and if $recursively is set to TRUE, associative keys to store directory
   * paths. Example:<br/>
   * print_r(Directory::scan('../css', true));<br/>
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
   * @throws codeminus\main\ExtendedException
   */
  public static function scan($directory, $recursively = false) {
    if (!is_dir($directory)) {
      throw new main\ExtendedException('Unable to find <b>' . $directory . '</b> directory');
    }
    $tree = array();
    $dirHandler = dir($directory);
    while (($subdir = $dirHandler->read()) !== false) {
      if ($subdir != '.' && $subdir != '..') {
        if (is_dir($directory . DIRECTORY_SEPARATOR . $subdir) && $recursively) {
          $tree[$subdir] = self::scan($directory . DIRECTORY_SEPARATOR . $subdir, $recursively);
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
   * @throws codeminus\main\ExtendedException
   */
  public static function recursiveCopy($source, $destination, $replaceExistent = false) {
    if (!is_dir($source)) {
      throw new main\ExtendedException('Unable to find <b>' . $source . '</b> directory');
    }
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