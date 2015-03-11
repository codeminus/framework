<?php

namespace codeminus\main;

/**
 * Extended Exception
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class ExtendedException extends \Exception {

  /**
   * Extended Exception
   * @param string $message
   * @param int $code
   * @param \Exception $previous
   * @return ExtendedException
   */
  public function __construct($message, $code = null, \Exception $previous = null) {
    parent::__construct($message, $code, $previous);
  }

  /**
   * Formatted message 
   * @return string A formatted exception message
   */
  public function getFormattedMessage() {
    switch ($this->getCode()) {
      case E_ERROR:
        $class = 'error';
        $prefix = '<b>Error:</b> ';
        break;
      default:
        $class = 'warning';
        $prefix = '<b>Warning:</b> ';
        break;
    }

    ob_start();
    ?>
    <div class="container-box rounded container-alert margined-bottom block
         <?php echo $class ?>">
      <section>
        <?php echo $prefix . $this->getMessage() ?> in
        <b><?php echo $this->getFile() ?></b> on line
        <b><?php echo $this->getLine() ?></b>
      </section>
    </div>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  /**
   * Formatted stack trace
   * @param bool $lastTrace if TRUE is given, it will only return the last
   * trace
   * @return string a formatted exception stack trace
   */
  public function getFormattedTrace($lastTrace = false) {

    ob_start();
    ?>
    <table class="table-border-rounded childs-valign-top">
      <?php
      $trace = $this->getTrace();
      //print_r($trace);
      $count = count($trace);
      for ($i = $count - 1; $i >= 0; $i--) {
        ?>
        <tr>
          <td>#<?php echo $i + 1 ?></td>
          <td>
            <b><?php echo $trace[$i]['file'] ?></b>
            on line
            <b><?php echo $trace[$i]['line'] ?></b>
            <div class="divider"></div>
            <?php if (isset($trace[$i]['class'])) { ?>
              <span>
                <?php echo $trace[$i]['class'] . ' ' . $trace[$i]['type'] ?>
              </span>
            <?php } ?>
            <span class="info"><?php echo $trace[$i]['function'] ?></span>(
            <span class="text-disabled">
              <?php
              $countArgs = count($trace[$i]['args']);
              for ($j = 0; $j < $countArgs; $j++) {
                $arg = $trace[$i]['args'][$j];
                if (is_array($arg)) {
                  echo gettype($arg) . ' ';
                } elseif (is_object($arg)) {
                  echo gettype($arg) . ' ' . get_class($arg);
                } else {
                  var_dump($arg);
                }
                echo ($j != $countArgs - 1) ? ', ' : null;
              }
              ?>

            </span>
            )
          </td>
        </tr>
        <?php
        if ($lastTrace) {
          $i = -1;
        }
      }
      ?>
    </table>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

  /**
   * Last exception thrown
   * @return array An associative array with the following structure:<br/>
   * ['file'] <br/>
   * ['line']<br/>
   * ['function']<br/>
   * ['class']<br/>
   * ['type']<br/>
   * ['args']
   */
  public function getLastTrace() {
    $traces = $this->getTrace();
    return $traces[count($traces) - 1];
  }

}