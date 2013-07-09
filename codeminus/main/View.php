<?php

namespace codeminus\main;

/**
 * Base view object
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class View {

  private static $TITLE;

  /**
   * Base view
   * @return View
   */
  public function __construct() {
    self::$TITLE = VIEW_DEFAULT_TITLE;
  }

  /**
   * View title
   * @param string $title
   * @return void
   */
  public function setTitle($title) {
    self::$TITLE = $title;
  }

  /**
   * View title
   * @return string
   */
  public static function getTitle() {
    if (self::$TITLE) {
      return self::$TITLE;
      //if none is set it creates on based on controller name and controller
      //called method name
    } else {
      return Router::$CONTROLLER_NAME . ' :: ' . Router::$CONTROLLER_METHOD_NAME;
    }
  }

  /**
   * Includes header file as defined on VIEW_DEFAULT_HEADER
   * @return void
   * @throws ExtendedException
   */
  public static function includeHeader() {
    $path = VIEW_PATH . VIEW_DEFAULT_HEADER;
    if (file_exists($path)) {
      require_once $path;
    } else {
      throw new ExtendedException('<b>Error: </b> requested header file not found on <b>' . $path . '</b>');
    }
  }

  /**
   * Includes footer file as defined on VIEW_DEFAULT_FOOTER
   * @return void
   * @throws ExtendedException
   */
  public static function includeFooter() {
    $path = VIEW_PATH . VIEW_DEFAULT_FOOTER;
    if (file_exists($path)) {
      require_once $path;
    } else {
      throw new ExtendedException('<b>Error: </b> requested footer file not found on <b>' . $path . '</b>');
    }
  }

  /**
   * Renders the view
   * @param string $view
   * @param boolean $autoIncludes if set to false it wont require the
   * DEFAULT_INCLUDE_HEADER and the DEFAULT_INCLUDE_FOOTER;
   * @param boolean $showAbout if set to TRUE show a list of all included files
   * @return void
   * @throws ExtendedException
   */
  public function render($view, $autoIncludes = true, $showAbout = false) {
    $filepath = VIEW_PATH . '/' . $view . '.php';

    if ($autoIncludes) {
      self::includeHeader();
    }

    if (file_exists($filepath)) {
      require_once $filepath;
    } else {
      throw new ExtendedException('<b>Error:</b> View not found in ' . $filepath);
    }

    if ($autoIncludes) {
      self::includeFooter();
    }

    if ($showAbout) {
      echo self::about();
    }
  }

  /**
   * Returns informations about the files that generated the view. It wont show
   * the files included after the call of this method. To show all included
   * files, pass TRUE to $showAbout parameter on render() method
   * this
   * @return string
   */
  public static function about() {
    ob_start();
    ?>
    <section class="container-bubble container-box block">
      <header>About this page</header>
      <section class="text-align-left">
        <p>Files that generated the contents of this view:</p>
        <ul>
          <?php
          $incFiles = get_included_files();
          foreach ($incFiles as $file) {
            ?>
            <li>
              <?php echo $file ?>

            </li>
            <?php
          }
          ?>
        </ul>
      </section>
    </section>
    <?php
    $html = ob_get_contents();
    ob_end_clean();
    return $html;
  }

}