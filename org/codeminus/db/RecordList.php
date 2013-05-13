<?php

namespace org\codeminus\db;

use org\codeminus\db as db;
use org\codeminus\main as main;

/**
 * Database result record list
 * Its main purpose is to assist with record pagination
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class RecordList {

  private $dbconn;
  private $sqlStatement;
  private $sqlResult;
  private $totalRows;
  private $recordsPerPage;
  private $totalPages = 1;
  private $currentPage;

  //Error messages

  const ERR_LIMITCLAUSE = '<b>Error:</b> There can be no LIMIT clause on the SQL statement. This declaration is set automatically';
  const ERR_RPP = '<b>Error:</b> records per page must be greater than zero';

  /**
   * Database record list
   * @param Connection $dbconn database conection object
   * @param string $sqlStmt SQL statement to generate the record list from
   * @param int $currentPage[optional] if an invalid page is given either the
   * first or the last page will be set as current page.
   * @param int $recordsPerPage[optional] if not given, the DEFAULT records
   * per page will be set.
   * @return RecordList
   */
  public function __construct(db\Connection $dbconn, $sqlStmt, $currentPage = 1, $recordsPerPage = DEFAULT_RPP) {

    $this->dbconn = $dbconn;

    try {
      $this->setSqlStatement($sqlStmt);
      $this->setRecordsPerPage($recordsPerPage);
    } catch (main\ExtException $e) {
      echo $e->getDetailedMessage();
      exit;
    }

    $this->setTotalRows();

    //Set current page must be called after knowing the total pages
    //to avoid invalid current page values
    $this->setCurrentPage($currentPage);

    try {
      $this->setSqlResult();
    } catch (main\ExtException $e) {
      echo $e->getDetailedMessage();
      exit;
    }
  }

  /**
   * SQL statement
   * @return string
   */
  public function getSqlStatement() {
    return $this->sqlStatement;
  }

  /**
   * SQL statement
   * @param string $sqlStatement without LIMIT declaration
   * @return void
   * @throws ExtException
   */
  private function setSqlStatement($sqlStatement) {

    $tmpSql = str_replace('\'', '"', $sqlStatement);
    $tmpSql = explode('"', $tmpSql);

    if (stripos($tmpSql[count($tmpSql) - 1], "LIMIT") > -1) {
      throw new main\ExtException(self::ERR_LIMITCLAUSE);
    } else {
      $this->sqlStatement = $sqlStatement;
    }
  }

  /**
   * Removes all selected fields but the first and insert it into 
   * the COUNT() function
   * @param string $sqlStmt
   * @return string SELECT COUNT(first_field) FROM ... [the rest of the query]
   */
  public static function getCountStatement($sqlStmt) {

    $sqlBeforeFrom = substr($sqlStmt, 0, stripos($sqlStmt, ' FROM '));
    $fields = preg_replace('/SELECT\s/i', '', $sqlBeforeFrom);
    $fieldsArray = explode(',', $fields);

    return 'SELECT COUNT(' . $fieldsArray[0] . ') ' . substr($sqlStmt, stripos($sqlStmt, ' FROM '));
  }

  /**
   * LIMIT declaration for the SQL statement
   * @return string
   */
  private function getSqlLimit() {

    return " LIMIT " . $this->getStartRow() . ',' . $this->getRecordsPerPage();
  }

  /**
   * Result of SQL statement
   * @return mysqli_result
   */
  public function getSqlResult() {
    return $this->sqlResult;
  }

  /**
   * Result of SQL statement
   * @return void
   * @throws ExtException
   */
  private function setSqlResult() {

    $this->sqlResult = $this->dbconn->query($this->getSqlStatement() . $this->getSqlLimit());
    if (!$this->sqlResult) {
      throw new main\ExtException($this->dbconn->error);
    }
  }

  /**
   * Total of rows from the result set 
   * @return int
   */
  public function getTotalRows() {
    return $this->totalRows;
  }

  /**
   * Performs a faster query to count the number of rows from the result and
   * calculates the total pages
   * @return void
   * @throws ExtException
   */
  private function setTotalRows() {

    $result = $this->dbconn->query(self::getCountStatement($this->getSqlStatement()));

    if (!$result) {
      throw new main\ExtException($this->dbconn->error);
    } else {

      $totalRows = $result->fetch_array();
      $this->totalRows = $totalRows[0];

      $this->setTotalPages(ceil($this->getTotalRows() / $this->getRecordsPerPage()));
    }
  }

  /**
   * The row number of the first record in current page
   * @return int the row number beginning with zero for the first row and so on
   */
  public function getStartRow() {

    if ($this->currentPage == 1) {
      $startRow = 0;
    } else {
      $startRow = ($this->getCurrentPage() - 1) * $this->getRecordsPerPage();
    }

    if ($startRow >= 0) {
      return $startRow;
    } else {
      return 0;
    }
  }

  /**
   * Record List number of records per page
   * @return int
   */
  public function getRecordsPerPage() {
    return $this->recordsPerPage;
  }

  /**
   * Record List number of records per page
   * @param int $recordsPerPage
   * @return void
   */
  protected function setRecordsPerPage($recordsPerPage) {
    if ($recordsPerPage > 0) {
      $this->recordsPerPage = round($recordsPerPage);
    } else {
      throw new main\ExtException(self::ERR_RPP);
    }
  }

  /**
   * Record list total number of pages
   * @return int
   */
  public function getTotalPages() {
    return $this->totalPages;
  }

  /**
   * Record list total number of pages
   * @param int $totalPages
   * @return void
   */
  private function setTotalPages($totalPages) {
    $this->totalPages = $totalPages;
  }

  /**
   * Record list previous page
   * @return int
   */
  public function getPreviousPage() {
    return $this->getCurrentPage() - 1;
  }

  /**
   * Record list next page
   * @return int
   */
  public function getNextPage() {
    if ($this->getCurrentPage() < $this->getTotalPages()) {
      return $this->currentPage + 1;
    } else {
      return 0;
    }
  }

  /**
   * Record list current page
   * @return int
   */
  public function getCurrentPage() {
    return $this->currentPage;
  }

  /**
   * Record list current page
   * @param int $currentPage
   * @return void
   */
  private function setCurrentPage($currentPage) {

    if ($currentPage > 0 && $currentPage <= $this->getTotalPages()) {
      //A valid page was given
      $this->currentPage = (Integer) $currentPage;
    } elseif ($currentPage > $this->getTotalPages()) {
      //Giving a page value greater than the total pages will set current page
      //to last page
      $this->currentPage = $this->getTotalPages();
    } else {
      //Giving a page value smaller than 1 will set current page to first page
      $this->currentPage = 1;
    }
  }

}