<?php

namespace codeminus\file;

use codeminus\util as util;

/**
 * FileHandler
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.2
 */
class FileHandler {

  const MATCH_ANY = 0;
  const MATCH_BEGINNING = 1;
  const MATCH_END = 2;
  const MATCH_ALL = 3;

  /**
   * Searchs for files and directories within a given directory
   * @param string $what the expression to search for
   * @param string $where the directory to search in
   * @param array $storage the array that is going to store the result of the
   * search
   * @param int $matchMode One of the FileHandler match constants.
   * If MATCH_ANY is given, it will match any part of the name of the directory
   * or file. If MATCH_BEGINNING is given, it will match only the beginning. If
   * MATCH_END is given, it will match only the end. If MATCH_ALL is given, it
   * will match the whole name of the directory or file
   * @param boolean $deepSearch if TRUE is given, will search on all
   * subdirectories
   * @param boolean $caseSensitive if TRUE is given, the search will be case
   * sensitive.
   * @param boolean $ignoreExtension if TRUE is given, the search will consider
   * only the file name and not its extension
   * @return void
   */
  public static function find($what, $where, &$storage, $matchMode = self::MATCH_ANY, $deepSearch = false, $caseSensitive = false, $ignoreExtension = false) {
    if (!$caseSensitive) {
      $what = strtolower($what);
    }
    $dirHandler = dir($where);
    while ($file = $dirHandler->read()) {
      if ($file != '.' && $file != '..') {
        $fullPath = $where . DIRECTORY_SEPARATOR . $file;
        if (!$caseSensitive) {
          $file = strtolower($file);
        }
        if(!is_dir($fullPath)){
          if ($ignoreExtension) {
            $file = str_replace('.' . self::getFileExtension($file), '', $file);
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
          $storage[] = $fullPath;
        }
        if (is_dir($fullPath) && $deepSearch) {
          self::find($what, $fullPath, $storage, $matchMode, $deepSearch, $caseSensitive, $ignoreExtension);
        }
      }
    }
  }

  /**
   * Scans all files and subdirectories of a given directory
   * @param string $directory path
   * @return array an array containing numeric keys to store file paths and
   * and associative keys to store directory paths. Example:<br/>
   * print_r(FileHandler::recursiveScan('../css'));<br/>
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
    while ($subdir = $dirHandler->read()) {
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
   * @param string $src source path to copy from
   * @param string $dst destination path to copy to
   * @param boolean $replaceExistent if TRUE it will replace all existent files
   * @return void
   */
  public static function recursiveCopy($src, $dst, $replaceExistent = false) {
    $dir = opendir($src);
    if (!file_exists($dst)) {
      mkdir($dst);
      util\ClassLog::add(__METHOD__, $dst . ' created');
    } else {
      util\ClassLog::add(__METHOD__, $dst . ' not created. Directory already exists', util\ClassLog::LOG_WARNING);
    }
    while (false !== ( $file = readdir($dir))) {
      if (( $file != '.' ) && ( $file != '..' )) {
        $sourceFilePath = $src . '/' . $file;
        $destinationFilePath = $dst . '/' . $file;
        if (is_dir($sourceFilePath)) {
          self::recursiveCopy($sourceFilePath, $destinationFilePath, $replaceExistent);
        } else {
          if (file_exists($destinationFilePath) && $replaceExistent) {
            self::delete($destinationFilePath);
          }
          if (!file_exists($destinationFilePath)) {
            copy($sourceFilePath, $destinationFilePath);
            util\ClassLog::add(__METHOD__, $destinationFilePath . ' created');
          } else {
            util\ClassLog::add(__METHOD__, $destinationFilePath . ' not created. File already exists', util\ClassLog::LOG_WARNING);
          }
        }
      }
    }
    closedir($dir);
  }

  /**
   * Delete directory of file
   * @param string $mixed path to delete
   * @return boolean TRUE if path deleted with success and FALSE otherwise
   */
  public static function delete($mixed) {
    $deleted = false;
    if (is_dir($mixed)) {
      if (rmdir($mixed)) {
        $deleted = true;
      }
    } else {
      if (unlink($mixed)) {
        $deleted = true;
      }
    }
    if ($deleted) {
      util\ClassLog::add(__METHOD__, $mixed . ' deleted', util\ClassLog::LOG_ERROR);
    }
    return $deleted;
  }

  /**
   * Creates a file
   * @param string $fileName complete file path
   * @param string $fileContent Content to be put inside the file
   * method
   * @param boolean $replaceExistent if TRUE it will replace all existent files
   * @return void
   */
  public static function createFile($fileName, $fileContent, $replaceExistent) {
    if ($replaceExistent) {
      self::delete($fileName);
    }
    if (!file_exists($fileName)) {
      if (!file_put_contents($fileName, $fileContent)) {
        exit('<p>Unable to create ' . $fileName . '</p>');
      } else {
        util\ClassLog::add(__METHOD__, $fileName . ' created');
      }
    } else {
      util\ClassLog::add(__METHOD__, $fileName . ' not created. File already exists', util\ClassLog::LOG_WARNING);
    }
  }

  /**
   * Creates a directory
   * @param string $dir path and name
   * @param octal $mode[optional] the access level desired for the folder
   * @return void;
   */
  public static function createDir($dir, $mode = 0755) {
    if (!file_exists($dir)) {
      if (!mkdir($dir, $mode, true)) {
        exit('<p class="warning">Unable to create ' . $dir . '</p>');
      } else {
        util\ClassLog::add(__METHOD__, $dir . ' created');
      }
    } else {
      util\ClassLog::add(__METHOD__, $dir . ' not created. Directory already exists', util\ClassLog::LOG_WARNING);
    }
  }

  /**
   * File extension
   * @param string $fileName Containing a filename
   * @return string extension
   */
  public static function getFileExtension($fileName) {
    $parts = explode(".", $fileName);
    return strtolower(end($parts));
  }

  /**
   * Validate file extension
   * @param string $filename Containing a filename
   * @param string $validExtensions List of extensions separated by ,(comma).
   * <br/>Example: "gif, jpg, png"
   * @return boolean TRUE if the extension is valid and FALSE otherwise.
   */
  public static function validateExtension($filename, $validExtensions) {
    $fileExtension = self::getFileExtension($filename);
    $validExtensions = explode(',', $validExtensions);
    foreach ($validExtensions as $ext) {
      if ($fileExtension == trim($ext)) {
        return true;
      }
    }
    return false;
  }

}