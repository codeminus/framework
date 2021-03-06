<?php

namespace codeminus\util;

/**
 * Class logging<br/>
 * Log's class method's output messages in a more structured fashion.<br/>
 * It's main purpose is to simplify messages output<br/>
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class ClassLog {

  //use \codeminus\traits\Singleton;
  
  static $on = false;
  static $logs = array();

  const LOG_INFO = 0;
  const LOG_WARNING = 1;
  const LOG_ERROR = 2;

  /**
   * Turns class log on
   * @return void
   */
  public static function on() {
    self::$on = true;
  }

  /**
   * Turns class log off
   * @return void
   */
  public static function off() {
    self::$on = false;
  }

  /**
   * Adds a log entry to ClassLog::$logs static array.<br/>
   * Remember to call ClassLog::on() before adding logs
   * @param string $method  A string containing the class and method name. It
   * must obey this format: TheNamespace\ClassName::methodName.
   * It is highly recommended that you use __METHOD__ magic constant as it will
   * return a method's full name like: TheNamespace\ClassName::methodName
   * @param string $message The message to be logged
   * @param int $type [optional] A log type constant.<br/>
   * LOG_INFO - the default if none is given<br/>
   * LOG_WARNING<br/>
   * LOG_ERROR
   * @return bool if the log of stored with success it will return TRUE. If
   * not, it means that ClassLog is turned off.
   */
  public static function add($method, $message, $type = self::LOG_INFO) {
    if (!self::$on) {
      return false;
    }
    $classInfo = explode('::', $method);
    $log['namespace'] = substr($classInfo[0], 0, strrpos($classInfo[0], '\\'));
    $log['class'] = $classInfo[0];
    $log['method'] = $classInfo[1];
    $log['method_fullname'] = $method;
    $log['type'] = $type;
    $log['message'] = $message;
    array_push(self::$logs, $log);
    return true;
  }

  /**
   * An associative array containing all logs from a given class
   * @param string $className the full name of a classe.<br/>
   * Example: TheNamespace\ClassName
   * @param int $type [optional] A log type constant. If none is given, all
   * messages from the given class will be returned
   * @return array Follow the structure below:<br/>
   * [index]['namespace'] the class namespace<br/>
   * [index]['class'] the class name<br/>
   * [index]['method'] the method name<br/>
   * [index]['method_fullname'] the method fullname<br/>
   * [index]['type'] the message type<br/>
   * [index]['message'] the message content
   */
  public static function get($className, $type = null) {
    if (!isset($type)) {
      $specClassArray = array();
      foreach (self::$logs as $log) {
        if ($log['class'] == $className) {
          array_push($specClassArray, $log);
        }
      }
      return $specClassArray;
    } else {
      $specTypeArray = array();
      foreach (self::$logs as $log) {
        if ($log['class'] == $className && $log['type'] == $type) {
          array_push($specTypeArray, $log);
        }
      }
      return $specTypeArray;
    }
  }

  /**
   * An associative array containing all logs within ClassLog::$logs
   * @param int $type [Optional] A log type constant. If none is given, all
   * messages will be returned
   * @return array Follow the structure below:<br/>
   * [index]['namespace'] the class namespace<br/>
   * [index]['class'] the class name<br/>
   * [index]['method'] the method name<br/>
   * [index]['method_fullname'] the method fullname<br/>
   * [index]['type'] the message type<br/>
   * [index]['message'] the message content
   */
  public static function getAll($type = null) {
    if (!isset($type)) {
      return self::$logs;
    } else {
      $specTypeArray = array();
      foreach (self::$logs as $log) {
        if ($log['type'] == $type) {
          array_push($specTypeArray, $log);
        }
      }
      return $specTypeArray;
    }
  }

}