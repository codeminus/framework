<?php

namespace codeminus\main;

/**
 * Codeminus Framework informations
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
final class Framework {

  //use \codeminus\traits\Singleton;

  const VERSION = '0.9.1';
  
  /**
   * Returns the user defined constants as an associative array
   * @return mixed An array if there's any user defined constants or FALSE
   * otherwise
   */
  public static function appConstants() {
    $definedConstants = get_defined_constants(true);
    if (isset($definedConstants['user'])) {
      return $definedConstants['user'];
    } else {
      return false;
    }
  }

  /**
   * Returns the user defined constants formatted with HTML
   * @return string
   * @throws ExtendedException
   */
  public static function appConstantsView() {
    if (ENV_MODE == Application::PRO_MODE) {
      throw new ExtendedException("For security reasons, you can't invoke "
      . __METHOD__ . ' on production environment');
    }
    ob_start();
    ?>
    <section class="container-bubble container-box block">
      <header>Application constants</header>
      <section>
        <table class="table-border-rounded table-condensed table-hover-gray">
          <?php
          foreach (self::appConstants() as $const => $value) {
            ?>
            <tr>
              <td class="width-min info"><?php echo $const ?></td>
              <td><?php echo $value ?></td>
            </tr>
            <?php
          }
          ?>
        </table>
      </section>
    </section>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

}