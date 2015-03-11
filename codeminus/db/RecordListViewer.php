<?php

namespace codeminus\db;

/**
 * Database record list viewer
 * Used along with RecordList class creates paging
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.3
 */
class RecordListViewer {

  private $recordList;
  private $linkSource;
  private $pageVarName = 'pg';
  private $firstPageButtonContent = '&nbsp;|&lt;&nbsp;';
  private $previousPageButtonContent = '&nbsp;&lt;&nbsp;';
  private $nextPageButtonContent = '&nbsp;&gt;&nbsp;';
  private $lastPageButtonContent = '&nbsp;&gt;|&nbsp;';
  private $tableParameters;

  const COMPLETE_CONTROLS = 0;
  const SIMPLE_CONTROLS = 1;

  /**
   * Database record list viewer
   * @param codeminus\db\RecordList $recordList
   * @param string $language used for viewer translation
   * @return RecordListViewer
   */
  public function __construct(codeminus\db\RecordList $recordList) {
    $this->setRecordList($recordList);
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
   * @param codeminus\db\RecordList $recordList
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
    (strpos($linkSource, '/?') > -1) ? $questionMark = '' : null;
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
   * http://yourdomain.com/app/paging/?&page=2
   */
  public function setPageVarName($pageVarName) {
    $this->pageVarName = $pageVarName;
  }

  /**
   * Page complete link source
   * @param int $page the destination page 
   * @return string
   * @example 
   * setLinkSource('http://example.com/someRecordList/');
   * setPageVarName('p');
   * getPageLinkSource(10) will return
   * http://example.com/someRecordList/?&p=10
   */
  public function getPageLinkSource($page) {
    return $this->getLinkSource() . '&' . $this->getPageVarName() . '=' . $page;
  }

  /**
   * The content that is going to be used insed the first page anchor tag
   * @return string
   */
  public function getFirstPageButtonContent() {
    return $this->firstPageButtonContent;
  }

  /**
   * The content that is going to be used insed the first page anchor tag
   * @param string $content
   * @return void
   */
  public function setFirstPageButtonContent($content) {
    $this->firstPageButtonContent = $content;
  }

  /**
   * The content that is going to be used insed the previous page anchor tag
   * @return string
   */
  public function getPreviousPageButtonContent() {
    return $this->previousPageButtonContent;
  }

  /**
   * The content that is going to be used insed the previous page anchor tag
   * @param string $content
   * @return void
   */
  public function setPreviousPageButtonContent($content) {
    $this->previousPageButtonContent = $content;
  }

  /**
   * The content that is going to be used insed the next page anchor tag
   * @return string
   */
  public function getNextPageButtonContent() {
    return $this->nextPageButtonContent;
  }

  /**
   * The content that is going to be used insed the next page anchor tag
   * @param string $content
   * @return void
   */
  public function setNextPageButtonContent($content) {
    $this->nextPageButtonContent = $content;
  }

  /**
   * The content that is going to be used insed the last page anchor tag
   * @return string
   */
  public function getLastPageButtonContent() {
    return $this->lastPageButtonContent;
  }

  /**
   * The content that is going to be used insed the last page anchor tag
   * @param string $content
   * @return void
   */
  public function setLastPageButton($content) {
    $this->lastPageButtonContent = $content;
  }

  /**
   * Anchor tag code for first page button
   * This method does not consider if it is already on the first page
   * Consider calling getRewindButtons() instead
   * @param string $title link title parameter
   * @return string HTML code
   */
  public function getFirstPageButton($title = null) {
    return '<a href="' . $this->getPageLinkSource(1) . '"' .
            ' title="' . $title . '">' . $this->getFirstPageButtonContent() .
            '</a>';
  }

  /**
   * Anchor tag code for previous page button
   * This method does not consider if there is or not a previous page to show
   * Consider calling getRewindButtons() instead
   * @param string $title link title parameter
   * @return string HTML code
   */
  public function getPreviousPageButton($title = null) {
    $source = $this->getPageLinkSource($this->recordList->getPreviousPage());
    return '<a href="' . $source . '"' .
            ' title="' . $title . '">' . $this->getPreviousPageButtonContent() .
            '</a>';
  }

  /**
   * Anchor tag code for next page button
   * This method does not consider if there is or not a next page to show
   * Consider calling getForwardButtons() instead
   * @param string $title link title parameter
   * @return string HTML code
   */
  public function getNextPageButton($title = null) {
    $source = $this->getPageLinkSource($this->recordList->getNextPage());
    return '<a href="' . $source . '"' .
            ' title="' . $title . '">' . $this->getNextPageButtonContent() .
            '</a>';
  }

  /**
   * Anchor tag code for last page button
   * This method does not consider if it is already on the last page
   * Consider calling getForwardButtons() instead
   * @param string $title link title parameter
   * @return string HTML code
   */
  public function getLastPageButton($title = null) {
    $source = $this->getPageLinkSource($this->recordList->getTotalPages());
    return '<a href="' . $source . '"' .
            ' title="' . $title . '">' . $this->getLastPageButtonContent() .
            '</a>';
  }

  /**
   * HTML code with buttons to control de page rewinding
   * @return string HTML code
   * If page rewinding is not necessery, it will return inactive buttons
   */
  public function getRewindButtons() {
    if ($this->recordList->getTotalPages() > 1) {
      if ($this->recordList->getCurrentPage() > 1 ||
              $this->recordList->getCurrentPage() ==
              $this->recordList->getTotalPages()) {
        return $this->getFirstPageButton() . $this->getPreviousPageButton();
      } else {
        return '<span style="opacity: 0.5">' .
                $this->getFirstPageButtonContent() .
                $this->getPreviousPageButtonContent() .
                '</span>';
      }
    }
  }

  /**
   * HTML code with buttons to control de page forwarding
   * @return string HTML code
   * If page forwarding is not necessery, it will return inactive buttons
   */
  public function getForwardButtons() {
    if ($this->recordList->getTotalPages() > 1) {
      if ($this->recordList->getCurrentPage() == 1 ||
              $this->recordList->getCurrentPage() <
              $this->recordList->getTotalPages()) {
        return $this->getNextPageButton() . $this->getLastPageButton();
      } else {
        return '<span style="opacity: 0.5">' .
                $this->getNextPageButtonContent() .
                $this->getLastPageButtonContent() .
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
        return $this->getRewindButtons() . ' ' .
                $this->recordList->getCurrentPage() . ' - ' .
                $this->recordList->getTotalPages() . ' ' .
                $this->getForwardButtons();
        break;
      case self::SIMPLE_CONTROLS:
        return $this->recordList->getCurrentPage() . ' - ' .
                $this->recordList->getTotalPages() . ' ' .
                $this->getPreviousPageButton() .
                $this->getNextPageButton();
        break;
    }
  }

  /**
   * Table tag parameters like class, id, etc...
   * @return string
   */
  private function getTableParameters() {
    return $this->tableParameters;
  }

  /**
   * Table tag parameters like class, id, etc...
   * @param string $tableParameters
   * @return void
   * @example setTableParameters('class="tableStyling"');
   */
  public function setTableParameters($tableParameters) {
    $this->tableParameters = $tableParameters;
  }

  /**
   * HTML table containing all rows from the result set
   * @param string $columnsHeaders column headers separated by ,(comma).
   * If null is given, the original columns names will be used.
   * @param string $id HTML id parameter value
   * @param type $className HTML class parameter value
   * @return string
   */
  public function getTable($columnsHeaders = null) {
    $table = "<table " . $this->getTableParameters() . " >";
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