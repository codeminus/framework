<?php

namespace org\codeminus\util;

/**
 * Defines and handles all security related issues and damage control
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class DamageControl {

  const LOGIN_MINIMUM_SIZE = 6;
  const PASSWORD_MINIMUM_SIZE = 6;

  /**
   * @var int
   */
  const PASSWORD_VERY_WEAK = 1;

  /**
   * @var int
   */
  const PASSWORD_WEAK = 2;

  /**
   * @var int
   */
  const PASSWORD_AVERAGE = 3;

  /**
   * @var int
   */
  const PASSWORD_STRONG = 4;

  /**
   * @var default value for record per page. Used for pagination.
   */
  const MAX_REC_PER_PAGE = 100;
  const DOUBLE_TYPE = "double";
  const INTEGER_TYPE = "integer";
  const POSITIVE_DIRECTION = 0;
  const NEGATIVE_DIRECTION = 1;
  const ANY_DIRECTION = 1;

  /**
   * Verifies if a given value has whitespaces
   * @param type $value
   * @return boolean true if whitespace(space, tab, etc.) is found and false
   * otherwise
   */
  public static function hasWhitespace($value) {
    if (preg_match('/\s/', $value)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Removes all whitespaces from the given value
   * For only whitespace before, after or both, refer to ltrim, rtrim or trim
   * on php.net
   * @param string $value
   * @return string
   */
  public static function removeWhitespaces($value) {
    return preg_replace('/\s/', '', $value);
  }

  /**
   * Returns the strengh of a given password
   * For each type of character in the password it adds +1 to the strengh.
   * If the result at the end is 1, the password is very weak, if it is 4,
   * the password is strong
   * @param string $password
   * @return int
   */
  public static function getPasswordStrength($password) {

    $strengh = 0;

    if (preg_match('/[a-z]/', $password)) {
      $strengh += 1;
    }

    if (preg_match('/[A-Z]/', $password)) {
      $strengh += 1;
    }

    if (preg_match('/[0-9]/', $password)) {
      $strengh += 1;
    }

    if (preg_match('/\W/', $password)) {
      $strengh += 1;
    }

    return $strengh;
  }

  /**
   * Validates a password strength
   * @param string $password
   * @param int $strength
   * @return boolean
   */
  public static function validatePasswordStrength($password, $strength = self::PASSWORD_AVERAGE) {
    if (self::getPasswordStrength($password) >= $strength) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Sanitize strings and arrays by escaping html and quotation characters
   * @param mixed $unsecuredValue
   * @return string/array depending on the given parameter
   */
  public static function sanitizeText($unsecuredValue) {

    if (is_array($unsecuredValue)) {
      foreach ($unsecuredValue as $key => $value) {
        $securedArray[$key] = addslashes(htmlspecialchars($value));
      }
    } else {
      $securedArray = addslashes(htmlspecialchars($unsecuredValue));
    }

    return $securedArray;
  }

  /**
   * Removes all non-numeric values.
   * This function will not recognize scientific notation as a valid number.
   * This function will always convert comma into dot.
   * @param mixed $value
   * @return double/integer
   */
  public static function sanitizeNumber($value) {

    //Removing all non-numeric values
    $value = preg_replace('/[^0-9\.,-]/', "", $value);

    //Replacing comma by dot
    $treatedValue = str_replace(",", ".", $value);

    //Type casting sanitized value to the correct number type
    if (strpos($treatedValue, ".") > -1) {
      return (double) $treatedValue;
    } else {
      return (integer) $treatedValue;
    }
  }

  /**
   * Verifies if a given value is a number and if it is within specified type
   * and direction
   * @param mixed $value
   * @param int $type[optional]
   * @param int $direction[optional] as in positive or negative
   * @return boolean
   */
  public static function validateNumber($value, $type = self::DOUBLE_TYPE, $direction = self::ANY_DIRECTION) {

    //Checking if the given value is a number and if it is of correct type
    if (is_numeric($value) && (gettype($value) == $type)) {

      //Validating number direction
      switch ($direction) {
        case self::POSITIVE_DIRECTION:
          if ($value >= 0)
            return true;
          break;

        case self::NEGATIVE_DIRECTION:
          if ($value <= 0)
            return true;
          break;

        case self::ANY_DIRECTION:
          return true;
          break;
      }

      //If none of the switch cases returns true
      return false;
    }else {
      return false;
    }
  }

  /**
   * Verifies if a given value is within specified format
   * Use 0 for required number
   * Use # for optional number
   * Use a for required letter
   * Use b for optional letter
   * @param mixed $value
   * @param string $format
   * @return boolean
   */
  public static function validateFormat($value, $format) {

    $regExp = $format;
    $regExp = str_replace('0', '[0-9]', $regExp);
    $regExp = str_replace('#', '[0-9]?', $regExp);
    $regExp = str_replace('a', '[a-zA-Z]', $regExp);
    $regExp = str_replace('b', '[a-zA-Z]?', $regExp);
    $regExp = str_replace('.', '\.', $regExp);
    $regExp = str_replace('/', '\/', $regExp);
    $regExp = str_replace(' ', '\s', $regExp);
    $regExp = str_replace('(', '\(', $regExp);
    $regExp = str_replace(')', '\)', $regExp);

    $regExp = "/$regExp/";

    //echo $regExp.'<br/>';

    if (preg_match($regExp, $value)) {
      return true;
    } else {
      return false;
    }
  }

  /**
   * Remove invalid e-mail characters and validates it
   * @todo search on internet for invalid e-mail characters
   * @param string $email
   * @return string if the sanitized e-mail is valid and false if not
   */
  public static function filterEmail($email) {
    $email = strtolower($email);

    #$sanitized = preg_replace('/[^0-9a-z\.@_-]/','',$email);
    $sanitized = filter_var($email, FILTER_SANITIZE_EMAIL);

    if (filter_var($sanitized, FILTER_VALIDATE_EMAIL)) {
      return $sanitized;
    } else {
      return false;
    }
  }

  /**
   * Removes invalid domain characters
   * @param string $domain
   */
  public static function sanitizeDomain($domain) {

    $domain = strtolower($domain);
    $domain = preg_replace('/\.{2,}/', '.', $domain);
    $domain = preg_replace('/^-|-$|\s|[^a-z0-9\.-]/i', '', $domain);

    return $domain;
  }

  /**
   * Removes invalid domain characters and adds the prefix http://
   * or leaves https:// if it's already set
   * @param string $url
   */
  public static function sanitizeURL($url) {

    $url = strtolower($url);

    $prefix = 'http://';

    if (strpos($url, 'https://') > -1) {
      $prefix = 'https://';
    }

    $domain = str_replace('http://', '', $url);
    $domain = str_replace('https://', '', $domain);
    $domain = self::sanitizeDomain($domain);

    return $prefix . $domain;
  }

}