<?php

namespace codeminus\util;

/**
 * Application session
 *
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class Session {

  /**
   * Starts a new session if there isnt one already
   * @return Session
   */
  public function __construct() {
    self::open();
  }

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
   * Session user
   * @return mixed depending on how it was stored (ex.: array, Object).
   * FALSE if no user was defined
   */
  public static function getUser() {
    if (isset($_SESSION['user'])) {
      return $_SESSION['user'];
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
    $_SESSION['user'] = $user;
  }

  /**
   * Session message
   * @param bool $onlyOnce
   * @return mixed depending on how it was stored (ex.: array, Object).
   * FALSE if no message was defined
   */
  public static function getMessage($onlyOnce = false) {
    if (isset($_SESSION['message'])) {
      $msg = $_SESSION['message'];
      if ($onlyOnce) {
        $this->setMessage(null);
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
    $_SESSION['message'] = $message;
  }

  /**
   * Validates a session checking if there's a authenticated user on the session
   * @param string $redirectURL to redirect the page to if there's no 
   * authenticated user
   * @return void
   */
  public static function validate($redirectURL) {
    if (!isset($_SESSION['user'])) {
      header("Location: " . $redirectURL);
      exit;
    }
  }

  public static function logout() {
    if (isset($_SESSION['user'])) {
      unset($_SESSION['user']);
    }
  }

  /**
   * Session close
   * Unsets all $_SESSION values and destroies it
   * @return void
   */
  public static function close() {
    session_unset();
    session_destroy();
  }

}