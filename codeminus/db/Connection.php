<?php

namespace codeminus\db;

use codeminus\main as main;

/**
 * Database connection
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class Connection extends \mysqli {

  private static $INSTANCE;
  private $host;
  private $user;
  private $database;

  /**
   * Opens connection with a database server
   * Default values will be used when an optional parameter isn't set.
   * @param string $host [optional]
   * @param string $user [optional]
   * @param string $password [optional]
   * @param string $database [optional]
   * @return Connection
   */
  public function __construct($host = DB_HOST, $user = DB_USER, $password = DB_PASS, $database = DB_NAME) {
    $this->setHost($host);
    $this->setUser($user);
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
   * The last instance of Connection
   * Use this method to avoid multiple unnecessary connections to the same
   * database
   * @return Connection Returns the last instance of Connection or creates one
   * with there's none
   */
  public static function getInstance() {
    if (!isset(self::$INSTANCE)) {
      self::$INSTANCE = new self();
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
   * Database user
   * @return string
   */
  public function getUser() {
    return $this->user;
  }

  /**
   * Database user
   * @param string $user
   * @return void
   */
  private function setUser($user) {
    $this->user = $user;
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

  /**
   * (PHP 5)<br/>
   * Performs a query on the database
   * @link http://php.net/manual/en/mysqli.query.php
   * @param string $query <p>
   * The query string.
   * </p>
   * <p>
   * Data inside the query should be properly escaped.
   * </p>
   * @param int $resultmode [optional] <p>
   * Either the constant <b>MYSQLI_USE_RESULT</b> or
   * <b>MYSQLI_STORE_RESULT</b> depending on the desired
   * behavior. By default, <b>MYSQLI_STORE_RESULT</b> is used.
   * </p>
   * <p>
   * If you use <b>MYSQLI_USE_RESULT</b> all subsequent calls
   * will return error Commands out of sync unless you
   * call <b>mysqli_free_result</b>
   * </p>
   * <p>
   * With <b>MYSQLI_ASYNC</b> (available with mysqlnd), it is
   * possible to perform query asynchronously.
   * <b>mysqli_poll</b> is then used to get results from such
   * queries.
   * </p>
   * @return mixed <b>FALSE</b> on failure. For successful SELECT, SHOW, DESCRIBE or
   * EXPLAIN queries <b>mysqli_query</b> will return
   * a <b>mysqli_result</b> object. For other successful queries <b>mysqli_query</b> will
   * return <b>TRUE</b>.
   * @throws codeminus\main\ExtendedException
   */
  public function query($query, $resultmode = MYSQLI_STORE_RESULT) {
    $db = $this->database;
    if (empty($db)) {
      throw new main\ExtendedException('No database selected', E_ERROR);
    }
    $result = parent::query($query, $resultmode);
    if (!$result) {
      throw new main\ExtendedException(
      $this->error .
      "<br/><b>SQL statement:</b>  <pre>" . $query . "</pre>", E_ERROR);
    } else {
      return $result;
    }
  }

  /**
   * Applies mysqli::real_escape_string() on all array positions recursivly 
   * @param array $array the array to escape
   * @return array
   */
  public function escape_array($array) {
    foreach ($array as $key => $value) {
      if (is_array($array[$key])) {
        $this->escape_array($array[$key]);
      } else {
        $array[$key] = $this->real_escape_string($value);
      }
    }
    return $array;
  }

  /**
   * Applies mysqli::real_escape_string() on the variable.
   * Works with string and arrays
   * @param mixed $varToEscape the variable to escape.
   * @return void
   */
  public function escape_var(&$varToEscape) {
    if (is_array($varToEscape)) {
      $varToEscape = $this->escape_array($varToEscape);
    } else {
      $varToEscape = $this->real_escape_string($varToEscape);
    }
  }

}