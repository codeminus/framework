<?php

namespace codeminus\main;

/**
 * Codeminus Framework informations
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
final class Framework {

  const VERSION = '0.9';
  
  /**
   * Returns the user defined constants as an associative array
   * @return array
   */
  public static function appConstants() {
    return get_defined_constants(true)['user'];
  }

  /**
   * Returns the user defined constants formatted with HTML
   * @return string
   */
  public static function appConstantsView() {
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
            <td class="width-min info"><?php echo $const?></td>
            <td><?php echo $value?></td>
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
?>
