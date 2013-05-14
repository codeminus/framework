<?php

namespace \org\codeminus\util;

/**
 * Description of DirectoryHandler
 *
 * @author Wilson Santos <wilson@codeminus.org>
 */
class DirectoryHandler {

  public static function recursiveCopy($src, $dst) {
    $dir = opendir($src);
    @mkdir($dst);
    while (false !== ( $file = readdir($dir))) {
      if (( $file != '.' ) && ( $file != '..' )) {
        if (is_dir($src . '/' . $file)) {
          self::recursiveCopy($src . '/' . $file, $dst . '/' . $file);
        } else {
          copy($src . '/' . $file, $dst . '/' . $file);
        }
      }
    }
    closedir($dir);
  }

}

?>
