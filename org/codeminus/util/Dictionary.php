<?php

namespace org\codeminus\util;

/**
 * 
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.2
 */
abstract class Dictionary {

  private $language;
  private $texts = array();

  /* usage example
    const LANGUAGE_EN = 'en';
    const LANGUAGE_PTBR = 'pt-br';

    const FIRST_PAGE = 'FIRST_PAGE';
    const PREV_PAGE = 'PREV_PAGE';
    const NEXT_PAGE = 'NEXT_PAGE';
    const LAST_PAGE = 'LAST_PAGE';
   */

  public function __construct($language) {

    $this->setLanguage($language);
    /* usage example
      $this->addText(self::LANGUAGE_EN, self::FIRST_PAGE, 'First page');
      $this->addText(self::LANGUAGE_PTBR, self::FIRST_PAGE, 'Primeira página');
      $this->addText(self::LANGUAGE_EN, self::PREV_PAGE, 'Previous page');
      $this->addText(self::LANGUAGE_PTBR, self::PREV_PAGE, 'Página anterior');
      $this->addText(self::LANGUAGE_EN, self::NEXT_PAGE, 'Next page');
      $this->addText(self::LANGUAGE_PTBR, self::NEXT_PAGE, 'Próxima página');
      $this->addText(self::LANGUAGE_EN, self::LAST_PAGE, 'Last page');
      $this->addText(self::LANGUAGE_PTBR, self::LAST_PAGE, 'Última página');
     */
  }

  public function getLanguage() {
    return $this->language;
  }

  public function setLanguage($language) {
    $this->language = $language;
  }

  public function getTexts() {
    return $this->texts;
  }

  public function setTexts($texts) {
    $this->texts = $texts;
  }

  public function addText($language, $key, $text) {
    $this->texts[$language][$key] = $text;
  }

  public function text($key) {
    return $this->texts[$this->getLanguage()][$key];
  }

}