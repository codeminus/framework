<?php

namespace org\codeminus\main;

/**
 * Extended Exception object
 *
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.2
 * @todo review methods implementation
 */
class ExtException extends \Exception {

  /**
   * Extended Exception object
   * @param string $message
   * @param int $code
   * @param \Exception $previous
   * @return ExtException
   */
  public function __construct($message, $code = null, \Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

  /**
   * Last exception thrown
   * @return assocArray ['file'], ['line'], ['function'], ['class'], ['type'], ['args']
   */
  public function getLastTrace() {
    $traces = $this->getTrace();
    return $traces[count($traces) - 1];
  }

  /**
   * Last exception thrown
   * @return string
   */
  public function getLastTraceAsString() {
    $lastTrace = $this->getLastTrace();
    return "<b>" . $lastTrace['file'] . "</b>: " .
            "<b>" . $lastTrace['class'] . $lastTrace['type'] . $lastTrace['function'] . "()</b> on line " .
            "<b>" . $lastTrace['line'] . "</b> ";
  }

  /**
   * Detailed message containing message, file and line information.
   * @return string
   */
  public function getDetailedMessage() {
    return $this->getMessage() . " (" . $this->getLastTraceAsString() . ")";
  }

}