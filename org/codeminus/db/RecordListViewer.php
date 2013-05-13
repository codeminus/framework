<?php

namespace org\codeminus\db;

use org\codeminus\util as util;

/**
 * Database record list viewer
 * Used along with RecordList class creates paging
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.2b
 */
class RecordListViewer {

  private $recordList;
  private $dictionary;
  private $linkSource;
  private $pageVarName = 'pg';
  private $linkVars;
  private $firstPageButtonImage;
  private $previousPageButtonImage;
  private $nextPageButtonImage;
  private $lastPageButtonImage;
  private $defaultButtonStyle = 'style="margin-bottom: -2px"';

  const COMPLETE_CONTROLS = 0;
  const SIMPLE_CONTROLS = 1;

  /**
   * Database record list viewer
   * @param RecordList $recordList
   * @param string $language used for viewer translation
   * @return RecordListViewer
   */
  public function __construct(RecordList $recordList, $language = util\Dictionary::LANGUAGE_PTBR) {
    $this->setRecordList($recordList);
    $this->dictionary = new util\Dictionary($language);
  }

  /**
   * Database record list
   * @return RecordList
   */
  public function getRecordList() {
    return $this->recordList;
  }

  /**
   * Database record list
   * @param RecordList $recordList
   * @return void
   */
  private function setRecordList($recordList) {
    $this->recordList = $recordList;
  }

  /**
   * Link source that handles the record list
   * @return string 
   */
  public function getLinkSource() {
    return $this->linkSource;
  }

  /**
   * Link source that handles the record list
   * @param string $linkSource without the page variable
   * calling this method
   * @return void
   * @example http://domain.com/Controller/method/?myvar=1
   */
  public function setLinkSource($linkSource) {
    $questionMark = '?';
    (strpos($linkSource, '/?')) ? $questionMark = '' : null;
    $this->linkSource = $linkSource . $questionMark;
  }

  /**
   * Page variable name
   * @return string
   */
  public function getPageVarName() {
    return $this->pageVarName;
  }

  /**
   * Page variable name
   * Variable name that will be set via GET to be used as page controller
   * @param string $pageVarName
   * @return void
   * @example if you setPageVarName('page') the paging control buttons will
   * be something similar to 
   * http://yourdomain.com/app/paging.php?ex=example&page=2
   */
  public function setPageVarName($pageVarName) {
    $this->pageVarName = $pageVarName;
  }

  /**
   * 
   * ATTENTION: this method does not work with codeminus MVC framework
   * 
   * GET query string to be sent along with the paging variable
   * @return string
   */
  /* public function getLinkVars() {

    if(!isset($this->linkVars)){
    if(count($_REQUEST) > 0){
    $linkVars = "";
    foreach(array_keys($_REQUEST) as $key){
    if($key != $this->getPageVarName()){
    $linkVars .= '&'.$key.'='.$_REQUEST[$key];
    }
    }
    $this->setLinkVars($linkVars);
    }
    }

    return $this->linkVars;

    } */

  /**
   * GET query string to be sent along with the paging variable.
   * This method is useful to avoid GET querystring injection
   * @param string $linkVars in querystring format.
   * DO NOT pass the variable that is already been used for paging control.
   * It will be set automatically.
   * @return void
   * @example setLinkVars('mode=fast&orderby=name');
   */
  /* public function setLinkVars($linkVars) {
    $this->linkVars = $linkVars;
    } */

  /**
   * Page complete link source
   * @param int $page the destination page 
   * @return string
   * @example 
   * setLinkSource('http://example.com/paging.php');
   * setLinkVars('style=thumbnail&orderby=name');
   * setPageVarName('p');
   * getPageLinkSource(10) will return
   * http://example.com/paging.php?style=thumbnail&orderby=name&p=10
   */
  public function getPageLinkSource($page) {
    return $this->getLinkSource() . '&' . $this->getPageVarName() . '=' . $page;
  }

  /**
   * Image tag with image representing the first page
   * @return string
   */
  public function getFirstPageButtonImage() {

    if (isset($this->firstPageButtonImage)) {

      return $this->firstPageButtonImage;
    } else {

      $source = LIB_ICONS_PATH . '/control_first.png';
      $alt = $this->dictionary->text(util\Dictionary::FIRST_PAGE);
      return util\HTML::image($source, $alt, $this->getDefaultButtonStyle());
    }
  }

  /**
   * Image tag with image representing the first page
   * @param string $source image location
   * @param string $alt image alternative text
   * @return void
   */
  public function setFirstPageButtonImage($source, $alt = null) {
    if (!isset($alt))
      $alt = $this->dictionary->text(util\Dictionary::FIRST_PAGE);
    $this->firstPageButtonImage = util\HTML::image($source, $alt);
  }

  /**
   * Image tag with image representing the previous page
   * @return string
   */
  public function getPreviousPageButtonImage() {

    if (isset($this->previousPageButtonImage)) {

      return $this->previousPageButtonImage;
    } else {

      $source = LIB_ICONS_PATH . '/control_prev.png';
      $alt = $this->dictionary->text(util\Dictionary::PREV_PAGE);
      return util\HTML::image($source, $alt, $this->getDefaultButtonStyle());
    }
  }

  /**
   * Image tag with image representing the previous page
   * @param string $source image location
   * @param string $alt image alternative text
   * @return void
   */
  public function setPreviousPageButtonImage($source, $alt = null) {
    if (!isset($alt))
      $alt = $this->dictionary->text(util\Dictionary::PREV_PAGE);
    $this->previousPageButtonImage = util\HTML::image($source, $alt);
  }

  /**
   * Image tag with image representing the next page
   * @return string
   */
  public function getNextPageButtonImage() {

    if (isset($this->nextPageButtonImage)) {

      return $this->nextPageButtonImage;
    } else {

      $source = LIB_ICONS_PATH . '/control_next.png';
      $alt = $this->dictionary->text(util\Dictionary::NEXT_PAGE);

      return util\HTML::image($source, $alt, $this->getDefaultButtonStyle());
    }
  }

  /**
   * Image tag with image representing the next page
   * @param string $source image location
   * @param string $alt image alternative text
   * @return void
   */
  public function setNextPageButtonImage($source, $alt = null) {
    if (!isset($alt))
      $alt = $this->dictionary->text(util\Dictionary::NEXT_PAGE);
    $this->nextPageButtonImage = util\HTML::image($source, $alt);
  }

  /**
   * Image tag with image representing the last page
   * @return string
   */
  public function getLastPageButtonImage() {

    if (isset($this->lastPageButtonImage)) {
      return $this->lastPageButtonImage;
    } else {

      $source = LIB_ICONS_PATH . '/control_last.png';
      $alt = $this->dictionary->text(util\Dictionary::LAST_PAGE);
      return util\HTML::image($source, $alt, $this->getDefaultButtonStyle());
    }
  }

  /**
   * Image tag with image representing the last page
   * @param string $source image location
   * @param string $alt image alternative text
   * @return void
   */
  public function setLastPageButtonImage($source, $alt = null) {
    if (!isset($alt))
      $alt = $this->dictionary->text(util\Dictionary::LAST_PAGE);
    $this->lastPageButtonImage = util\HTML::image($source, $alt);
  }

  /**
   * Additional Tag parameters
   * @return string
   */
  public function getDefaultButtonStyle() {
    return $this->defaultButtonStyle;
  }

  /**
   * Additional Tag parameters
   * @param type $defaultButtonStyle
   * @return void
   */
  protected function setDefaultButtonStyle($defaultButtonStyle) {
    $this->defaultButtonStyle = $defaultButtonStyle;
  }

  /**
   * HTML code for first page button
   * This method does not consider if it is already on the first page
   * Consider calling getRewindButtons() instead
   * @param string $title link title parameter
   * @return string HTML code
   */
  public function getFirstPageButton($title = null) {

    if (!isset($title))
      $title = $this->dictionary->text(util\Dictionary::FIRST_PAGE);

    return util\HTML::link(
                    $this->getPageLinkSource(1), $this->getFirstPageButtonImage(), $title);
  }

  /**
   * HTML code for previous page button
   * This method does not consider if there is or not a previous page to show
   * Consider calling getRewindButtons() instead
   * @param string $title link title parameter
   * @return string HTML code
   */
  public function getPreviousPageButton($title = null) {

    if (!isset($title))
      $title = $this->dictionary->text(util\Dictionary::PREV_PAGE);

    return util\HTML::link(
                    $this->getPageLinkSource($this->recordList->getPreviousPage()), $this->getPreviousPageButtonImage(), $title);
  }

  /**
   * HTML code for next page button
   * This method does not consider if there is or not a next page to show
   * Consider calling getForwardButtons() instead
   * @param string $title link title parameter
   * @return string HTML code
   */
  public function getNextPageButton($title = null) {

    if (!isset($title))
      $title = $this->dictionary->text(util\Dictionary::NEXT_PAGE);

    return util\HTML::link(
                    $this->getPageLinkSource($this->recordList->getNextPage()), $this->getNextPageButtonImage(), $title);
  }

  /**
   * HTML code for last page button
   * This method does not consider if it is already on the last page
   * Consider calling getFarwardButtons() instead
   * @param string $title link title parameter
   * @return string HTML code
   */
  public function getLastPageButton($title = null) {

    if (!isset($title))
      $title = $this->dictionary->text(util\Dictionary::LAST_PAGE);

    return util\HTML::link(
                    $this->getPageLinkSource($this->recordList->getTotalPages()), $this->getLastPageButtonImage(), $title);
  }

  /**
   * HTML code with buttons to control de paging rewinding
   * @return string HTML code
   * If page rewinding is not necessery, it will return inactive buttons
   */
  public function getRewindButtons() {

    if ($this->recordList->getTotalPages() > 1) {

      if ($this->recordList->getCurrentPage() > 1 || $this->recordList->getCurrentPage() == $this->recordList->getTotalPages()) {

        return $this->getFirstPageButton() . $this->getPreviousPageButton();
      } else {

        return '<span style="opacity: 0.5">' .
                $this->getFirstPageButtonImage() .
                $this->getPreviousPageButtonImage() .
                '</span>';
      }
    }
  }

  /**
   * HTML code with buttons to control de paging forwarding
   * @return string HTML code
   * If page forwarding is not necessery, it will return inactive buttons
   */
  public function getForwardButtons() {

    if ($this->recordList->getTotalPages() > 1) {

      if ($this->recordList->getCurrentPage() == 1 || $this->recordList->getCurrentPage() < $this->recordList->getTotalPages()) {

        return $this->getNextPageButton() . $this->getLastPageButton();
      } else {

        return '<span style="opacity: 0.5">' .
                $this->getNextPageButtonImage() .
                $this->getLastPageButtonImage() .
                '</span>';
      }
    }
  }

  /**
   * HTML code with rewinding and forwarding control set
   * @param int $model
   * @return string HTML code
   */
  public function getControls($model = self::COMPLETE_CONTROLS) {

    switch ($model) {

      case self::COMPLETE_CONTROLS:
        return $this->getRewindButtons() . ' ' . $this->recordList->getCurrentPage() . ' - ' . $this->recordList->getTotalPages() . ' ' . $this->getForwardButtons();
        break;
      case self::SIMPLE_CONTROLS:
        return $this->recordList->getCurrentPage() . ' - ' . $this->recordList->getTotalPages() . ' ' . $this->getPreviousPageButton() . $this->getNextPageButton();
        break;
    }
  }

  /**
   * HTML table containing all rows from the result set
   * @param string $columnsHeaders column headers separated by ,(comma).
   * If null is given, the original columns names will be used.
   * @param string $id HTML id parameter value
   * @param type $className HTML class parameter value
   * @return string
   */
  public function getTable($columnsHeaders = null, $id = "", $className = "") {



    $table = "<table id=\"$id\" class=\"$className\" >";
    $table .= "<thead>";
    $table .= "<tr>";

    if (isset($columnsHeaders)) {
      $columnsHeadersArray = explode(",", $columnsHeaders);
      foreach ($columnsHeadersArray as $header) {
        $table .= "<th>" . trim($header) . "</th>";
      }
    } else {
      while ($header = $this->getRecordList()->getSqlResult()->fetch_field()) {
        $table .= "<th>$header->name</th>";
      }
    }

    $table .= "</tr>";
    $table .= "</thead>";
    $table .= "<tbody>";

    while ($rs = $this->getRecordList()->getSqlResult()->fetch_row()) {

      $table .= "<tr>";

      for ($i = 0; $i < count($rs); $i++) {
        $table .= "<td>$rs[$i]</td>";
      }

      $table .= "</tr>";
    }

    $table .= "</tbody>";
    $table .= '</table>';

    return $table;
  }

}