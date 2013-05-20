<?php

namespace org\codeminus\db;

/**
 * Database connection
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class Connection extends \mysqli {

  private static $INSTANCE;
  private $host;
  private $database;

  /**
   * Opens connection with a database server
   * Default values will be used when an optional parameter isn't set.
   * @param string $host[optional]
   * @param string $user[optional]
   * @param string $pass[optional]
   * @param string $database[optional]
   * @return object Connection
   */
  public function __construct($host = DB_HOST, $user = DB_USER, $password = DB_PASS, $database = DB_NAME) {
    $this->setHost($host);
    $this->setDatabase($database);
    parent::__construct($host, $user, $password, $database);
    $this->setInstance();
  }

  /**
   * Clear the current DBConnection static instance, setting it to null
   */
  public static function clearLastInstance() {
    self::$INSTANCE = null;
  }

  /**
   * Set current instance of DBConnection to a static object
   * @return void
   */
  private function setInstance() {
    self::$INSTANCE = $this;
  }

  /**
   * Singleton implementation
   * Use this method to avoid multiple unnecessary connections to the same 
   * database
   * @return the last created instance of DBConnection
   */
  public static function getInstance() {

    self::$INSTANCE;

    if (!isset(self::$INSTANCE)) {
      $class = __CLASS__;
      self::$INSTANCE = new $class;
    }

    return self::$INSTANCE;
  }

  /**
   * Database host address
   * @return string
   */
  public function getHost() {
    return $this->host;
  }

  /**
   * Database host address
   * @param string $host
   * @return void
   */
  private function setHost($host) {
    $this->host = $host;
  }

  /**
   * Database name
   * @return string
   */
  public function getDatabase() {
    return $this->database;
  }

  /**
   * Database name
   * @param string $database
   * @return void
   */
  private function setDatabase($database) {
    $this->database = $database;
  }

}