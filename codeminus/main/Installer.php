<?php

namespace codeminus\main;

use codeminus\file as file;

/**
 * Framework Installer
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.4
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

  /**
   * Create application's default files and folders
   * @return bool TRUE if no problems occur during installation or FALSE
   * otherwise
   */
  public function createApp($replace = false, $replaceConfig = false) {
    file\Directory::copy(Application::getRoot() . '/codeminus/app-skeleton',
            $this->installPath, $replace);
    $this->createConfigFile($replaceConfig);
    return true;
  }

  public function createConfigFile($replace = false) {
    $iniFilePath = $this->installPath . '/app/config/main.ini';
    $this->ini->save($iniFilePath, $replace);
  }

}
