<?php

namespace codeminus\util;

/**
 * Dictionary abstract model
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.3
 */
abstract class Dictionary {

  private $language;
  private $expressions = array();

  /**
   * Dictionary
   * @param mixed $language the value that represent a specific language
   * @return Dictionary
   */
  public function __construct($language) {
    $this->setLanguage($language);
  }

  /**
   * Dictionary language
   * @return mixed
   */
  public function lang() {
    return $this->language;
  }

  /**
   * Dictionary language
   * @param mixed $language
   * @return void
   */
  private function setLanguage($language) {
    $this->language = $language;
  }

  /**
   * Dictionary expressions
   * @return array
   */
  public function getExpressions() {
    return $this->expressions;
  }

  /**
   * Dictionary expressions
   * @param array $expressions
   * @return void
   */
  protected function setExpressions($expressions) {
    $this->expressions = $expressions;
  }

  /**
   * Dictionary expression
   * @param mixed $key
   * @return string
   */
  public function expression($key) {
    return $this->expressions[$this->language][$key];
  }

  /**
   * Dictionary expression
   * @param mixed $key
   * @param string $expression
   * @return void
   */
  public function addExpression($key, $expression) {
    $this->expressions[$this->language][$key] = $expression;
  }

}