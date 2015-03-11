<?php

namespace codeminus\util;

/**
 * System Message
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class SystemMessage {

  private $code;
  private $messages;
  private $additionalInfo;

  /**
   * No system message defined
   * @var int
   */
  const NONE = 'none';
  
  //Message types
  const SUCCESS_MESSAGE = 0;
  const ERROR_MESSAGE = 1;
  const INFO_MESSAGE = 2;
  const WARNING_MESSAGE = 3;

  /**
   * System Message
   * @param mixed $code of the message to be called 
   * @return SystemMessage
   */
  public function __construct($code = self::NONE, $additionalInfo = "") {

    $this->setCode($code);
    $this->setAdditionalInfo($additionalInfo);
  }

  /**
   * Called System Message Code
   * @return mixed 
   */
  public function getCode() {
    return $this->code;
  }

  /**
   * Called System Message Code
   * @param mixed $code
   * @return void
   */
  public function setCode($code) {
    $this->code = $code;
  }

  /**
   * Add message into the message array
   * @param string $code
   * @param string $language
   * @param string $message
   * @return void
   */
  protected function addMessage($code, $message, $type = self::INFO_MESSAGE, $icon = null) {
    $this->messages[$code]['text'] = $message;
    $this->messages[$code]['type'] = $type;
    $this->messages[$code]['icon'] = $icon;
  }

  /**
   * System message array
   * @return array
   */
  public function getMessages() {
    return $this->messages;
  }

  /**
   * Message additional info
   * @return string
   */
  public function getAdditionalInfo() {
    return $this->additionalInfo;
  }

  /**
   * Message additional info
   * @param string $additionalInfo
   * @return void
   */
  public function setAdditionalInfo($additionalInfo) {
    $this->additionalInfo = $additionalInfo;
  }

  /**
   * Called system message
   * @param bool $showAdditionalInfo
   * @return string
   */
  public function getMessage($showAdditionalInfo = true) {
    $message = $this->messages[$this->getCode()]['text'];
    if ($showAdditionalInfo) {
      $message .= $this->getAdditionalInfo();
    }
    return $message;
  }

  /**
   * Called system message type
   * @return int
   */
  public function getType() {
    return $this->messages[$this->getCode()]['type'];
  }

  /**
   * Called system message Icon
   * @return string image URL
   */
  public function getIcon() {
    return $this->messages[$this->getCode()]['icon'];
  }

}
