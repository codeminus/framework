<?php

namespace codeminus\util;

/**
 * Application session
 *
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.2
 */
class Session {

  private static $key;

  use \codeminus\traits\Singleton;

  /**
   * Starts a new session if there isnt one already
   * @return void
   */
  public static function open() {
    if (!session_id()) {
      session_start();
    }
  }

  /**
   * A key to be prepended to session variables' names to increase security
   * @return string
   */
  public static function getKey() {
    return self::$key;
  }

  /**
   * A key to be prepended to session variables' names to increase security
   * @param type $key
   * @return void
   */
  public static function setKey($key) {
    self::$key = $key;
  }

  /**
   * Session user
   * @return mixed depending on how it was stored (ex.: array, Object).
   * FALSE if no user was defined
   */
  public static function getUser() {
    self::open();
    if (isset($_SESSION[self::getKey() . 'user'])) {
      return $_SESSION[self::getKey() . 'user'];
    } else {
      return false;
    }
  }

  /**
   * Session user
   * @param mixed $user it is suggested that you store an object instance
   * of the user logged in
   * @return void
   */
  public static function setUser($user) {
    self::open();
    $_SESSION[self::getKey() . 'user'] = $user;
  }

  /**
   * Session message
   * @param bool $onlyOnce
   * @return mixed depending on how it was stored (ex.: array, Object).
   * FALSE if no message was defined
   */
  public static function getMessage($onlyOnce = false) {
    self::open();
    if (isset($_SESSION[self::getKey() . 'message'])) {
      $msg = $_SESSION[self::getKey() . 'message'];
      if ($onlyOnce) {
        self::setMessage(null);
      }
      return $msg;
    } else {
      return false;
    }
  }

  /**
   * Session message
   * @param mixed $message it is suggested that you store an object intance
   * of the message
   * @return void
   */
  public static function setMessage($message) {
    self::open();
    $_SESSION[self::getKey() . 'message'] = $message;
  }

  /**
   * Session variable
   * @param string $var
   * @return mixed
   */
  public static function get($var){
    self::open();
    return $_SESSION[$var];
  }

  /**
   * Session variable
   * @param string $var
   * @param mixed $value
   * @return void
   */
  public static function set($var, $value){
    self::open();
    $_SESSION[$var] = $value;
  }

  /**
   * Validates a session checking if there's a authenticated user on the session
   * @param string $redirectURL to redirect the page to if there's no 
   * authenticated user
   * @return void
   */
  public static function validate($redirectURL) {
    self::open();
    if (!isset($_SESSION[self::getKey() . 'user'])) {
      header("Location: " . $redirectURL);
      exit;
    }
  }

  /**
   * Unsets only $_SESSION['user'] var
   * @return void
   */
  public static function logout() {
    self::open();
    if (isset($_SESSION[self::getKey() . 'user'])) {
      unset($_SESSION[self::getKey() . 'user']);
    }
  }

  /**
   * Session close
   * Unsets all $_SESSION values and destroies it
   * @return void
   */
  public static function close() {
    self::open();
    session_unset();
    session_destroy();
  }

}
