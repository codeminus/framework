<?php

namespace codeminus\main;

use codeminus\main as main;
use codeminus\file as file;

/**
 * Framework Installer
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.3
 */
class Installer {

  private $installPath;
  private $ini;

  /**
   * Framework Installer
   * @return Installer
   */
  public function __construct($installPath, file\Ini $ini) {
    $this->installPath = $installPath;
    $this->ini = $ini;
  }

  public static function getFrameworkRoot() {
    return file\Directory::normalize(realpath('../../'));
  }

  /**
   * Application HTTP path
   * @return string
   */
  public static function getFrameworkHttpRoot() {
    $script = $_SERVER['SCRIPT_NAME'];
    return 'http://' . $_SERVER['HTTP_HOST'] . substr($script, 0, strpos($script, '/codeminus'));
  }

  /**
   * Create application's default files and folders
   * @return bool TRUE if no problems occur during installation or FALSE
   * otherwise
   */
  public function createApp($reinstall = false) {
    file\Directory::recursiveCopy('../app-skeleton', $this->installPath, $reinstall);
    $iniFilePath = $this->installPath . '/app/config/main.ini';
    $this->ini->save($iniFilePath, $reinstall);
    return true;
  }

}