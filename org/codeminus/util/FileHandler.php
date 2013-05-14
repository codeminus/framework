<?php

namespace org\codeminus\util;

/**
 * Description of FileHandler
 *
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class FileHandler {

  /**
   * Copies all files from a folder to another recursively
   * @param string $src source path to copy from
   * @param string $dst destination path to copy to
   * @param boolean $showLog if true it will output messages about what is happening inside the method.
   * @return void
   */
  public static function recursiveCopy($src, $dst, $showLog = true, $groupLog = true) {
    if ($groupLog) {
        echo '<p class="info">_</p>';
      }
    $dir = opendir($src);
    if (!file_exists($dst)) {
      mkdir($dst);
      if ($showLog) {
        echo '<p class="info">' . $dst . ' created.</p>';
      }
    } else {
      if ($showLog) {
        echo '<p class="warning">' . $dst . ' NOT created. Directory already exists.</p>';
      }
    }    
    while (false !== ( $file = readdir($dir))) {
      if (( $file != '.' ) && ( $file != '..' )) {
        $sourceFilePath = $src . '/' . $file;
        $destinationFilePath = $dst . '/' . $file;
        if (is_dir($sourceFilePath)) {
          self::recursiveCopy($sourceFilePath, $destinationFilePath);
        } else {
          if (!file_exists($destinationFilePath)) {
            copy($sourceFilePath, $destinationFilePath);
            if ($showLog) {
              echo '<p class="info">' . $destinationFilePath . ' created.</p>';
            }
          } else {
            if ($showLog) {
              echo '<p class="warning">' . $destinationFilePath . ' NOT created. File already exists.</p>';
            }
          }
        }
      }
    }
    closedir($dir);
  }
  
  /**
   * Creates a file
   * @param string $fileName complete file path
   * @param string $fileContent Content to be put inside the file
   * @return void
   */
  public static function createFile($fileName, $fileContent) {
    if (!file_exists($fileName)) {
      if (!file_put_contents($fileName, $fileContent)) {
        exit('<p>Unable to create ' . $fileName . '</p>');
      } else {
        echo '<p class="info">' . $fileName . ' created.</p>';
      }
    } else {
      echo '<p class="warning">' . $fileName . ' NOT created. File already exists.</p>';
    }
  }

  /**
   * Creates a directory
   * @param string $dir path and name
   * @param octal $mode according to the access level defined by chmod
   * @return void;
   */
  public static function createDir($dir, $mode = 0777) {
    if (!file_exists($dir)) {
      if (!mkdir($dir, $mode, true)) {
        exit('<p class="warning">Unable to create ' . $dir . '</p>');
      } else {
        echo '<p class="info">' . $dir . ' created.</p>';
      }
    } else {
      echo '<p class="warning">' . $dir . ' NOT created. Directory already exists.</p>';
    }
  }
  
}

?>
