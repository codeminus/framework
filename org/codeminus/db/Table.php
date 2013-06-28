<?php

namespace org\codeminus\db;

use org\codeminus\main as main;

/**
 * Database Table abstract model
 * Extend this class on all classes that represent a table on a database
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */
abstract class Table {

  //Database connection object
  private $dbConn;
  //Database properties
  private $tableName;
  private $tableColumns = array();
  private $tableColumnsInfo = array();
  private $insertFields = array();
  private $updateFields = array();
  private $requiredInsertFields = array();
  private $requiredUpdateFields = array();
  private $requiredDeleteFields = array();
  private $sqlStatement;
  private $sqlResult;
  private $numRows = 0;
  private $currentRow = 0;
  private $strictUpdate = true;
  private $strictDelete = true;
  private $recordList = null;

  //Common properties

  const INACTIVE = 0;
  const ACTIVE = 1;

  //Database table column informations
  const COLUMN_TYPE = 'type';
  const COLUMN_SIZE = 'size';
  const COLUMN_NULL = 'null';
  const COLUMN_KEY = 'key';
  const COLUMN_DEFAULT = 'default';
  const COLUMN_EXTRA = 'extra';

  //Database operations
  const INSERT = 0;
  const UPDATE = 1;
  const DELETE = 2;

  //Error messages    
  const ERR_NOSQLSTMT = '<b>Error:</b> SQL statement not defined';
  const ERR_INVALIDSQL = '<b>Error:</b> Invalid SQL statement';
  const ERR_NULLFIELD = '<b>Error:</b> Required table field not set';
  const ERR_NOFIELDS = '<b>Error:</b> No table fields defined';
  const ERR_INVALIDOP = '<b>Error:</b> Invalid database operation';
  const ERR_STRICTOP = '<b>Error:</b> Strict database operation. Where clause not set';

  /**
   * Database Table abstract model
   * @param string $tableName Database table name
   * @return Table
   */
  public function __construct($tableName) {

    $this->setDbConn(Connection::getInstance());
    $this->setTableName($tableName);
  }

  /**
   * Database connection link
   * @return Connection
   */
  final public function getDbConn() {
    return $this->dbConn;
  }

  /**
   * Database connection link
   * @param Connection $dbConn
   * @return void
   */
  final public function setDbConn($dbConn) {
    $this->dbConn = $dbConn;
  }

  /**
   * Database connection error message
   * @return string
   */
  final public function getDbConnError() {
    return $this->dbConn->error;
  }

  /**
   * Database table name
   * @return string
   */
  final public function getTableName() {
    return $this->tableName;
  }

  /**
   * Database table name
   * @param string $tableName
   * @return void
   */
  final protected function setTableName($tableName) {

    $this->tableName = $tableName;

    try {
      $this->setTableColumns();
    } catch (main\ExtendedException $e) {
      echo $e->getDetailedMessage();
      exit;
    }
  }

  /**
   * Database table columns informations
   * @return void
   * @throws ExtendedException
   */
  private function setTableColumns() {

    $result = $this->dbConn->query("DESCRIBE " . $this->getTableName());

    if (!$result) {
      throw new main\ExtendedException($this->dbConn->error);
    }

    while ($row = $result->fetch_assoc()) {

      array_push($this->tableColumns, $row['Field']);

      $typeAndSize = explode('(', $row['Type']);
      $type = $typeAndSize[0];

      (count($typeAndSize) > 1) ? $size = str_replace(')', '', $typeAndSize[1]) : $size = null;

      ($row['Null'] == 'YES') ? $null = true : $null = false;

      $this->tableColumnsInfo[$row['Field']]['type'] = trim($type);
      $this->tableColumnsInfo[$row['Field']]['size'] = $size;
      $this->tableColumnsInfo[$row['Field']]['null'] = $null;
      $this->tableColumnsInfo[$row['Field']]['key'] = $row['Key'];
      $this->tableColumnsInfo[$row['Field']]['default'] = $row['Default'];
      $this->tableColumnsInfo[$row['Field']]['extra'] = $row['Extra'];
    }
  }

  /**
   * Database table columns
   * @return array containing all field names
   */
  final public function getTableColumns() {
    return $this->tableColumns;
  }

  /**
   * Database table columns information
   * @return array associative multi-dimentional with all table columns and
   * their informations
   * tableColumnsInfo['field']['type']
   * tableColumnsInfo['field']['size']
   * tableColumnsInfo['field']['null']
   * tableColumnsInfo['field']['key']
   * tableColumnsInfo['field']['default']
   * tableColumnsInfo['field']['extra']
   */
  final public function getTableColumnsInfo() {
    return $this->tableColumnsInfo;
  }

  /**
   * Database table column information
   * @param string $column
   * @param int $info
   * @return mixed depending on the $info requested
   * if $info is not given and array with all informations of the column is returned
   */
  final public function getColumnInfo($column, $info = null) {

    if (!isset($info)) {
      return $this->tableColumnsInfo[$column];
    } else {
      return $this->tableColumnsInfo[$column][$info];
    }
  }

  /**
   * Table fields and values to INSERT
   * @return array
   * @throws ExtendedException
   */
  final public function getInsertFields() {

    if (count($this->insertFields) == 0) {
      throw new main\ExtendedException(self::ERR_NOFIELDS);
    } else {
      return $this->insertFields;
    }
  }

  /**
   * Table fields and values to INSERT
   * Although this is a protected method avoid its use, unless you know what
   * you're doing. Use addInsertField() instead.
   * @param array $insertFields as in array[tableField] => value
   * @return void
   */
  final protected function setInsertFields($insertFields) {
    $this->insertFields = $insertFields;
  }

  /**
   * Adds a table field and its value to be used in the INSERT statement
   * @param string $field database table field name
   * @param string $value database table field value
   * @param boolean $quoted[optional] adds single quote to $value if set to true
   * @example addInsertField('field', 'value') will add "field='value'" to the
   * INSERT statement.
   * @return void
   */
  final protected function addInsertField($field, $value, $quoted = true) {

    if ($value !== null) {
      if ($quoted) {
        $value = "'" . $value . "'";
      }

      $this->insertFields[$field] = $value;
    }
  }

  /**
   * Table fields and values to UPDATE
   * @return array
   * @throws ExtendedException
   */
  final public function getUpdateFields() {

    if (count($this->updateFields) == 0) {
      throw new main\ExtendedException(self::ERR_NOFIELDS);
    } else {
      return $this->updateFields;
    }
  }

  /**
   * Table fields and values to UPDATE
   * Although this is a protected method avoid its use, unless you know what
   * you're doing. Use addUpdateField() instead.
   * @param array $updateFields as in array[tableField] => value
   * @return void
   */
  final protected function setUpdateFields($updateFields) {
    $this->updateFields = $updateFields;
  }

  /**
   * Adds a table field and its value to be used in the UPDATE statement
   * @param string $field database table field name
   * @param string $value database table field value
   * @param boolean $quoted[optional] adds single quote to $value if set to true
   * @return void
   * @example addUpdateField('field', 'value') will add "field='value'" to the
   * UPDATE statement.
   */
  final protected function addUpdateField($field, $value, $quoted = true) {

    if ($value !== null) {
      if ($quoted) {
        $value = "'" . $value . "'";
      }

      $field = $field . "=" . $value;

      array_push($this->updateFields, $field);
    }
  }

  /**
   * Required fields for a given database operation
   * @param int $operation Table::INSERT, Table::UPDATE, Table::DELETE
   * @return array
   * @throws ExtendedException
   */
  final public function getRequiredFields($operation) {

    switch ($operation) {
      case self::INSERT:
        return $this->requiredInsertFields;
        break;

      case self::UPDATE:
        return $this->requiredUpdateFields;
        break;

      case self::DELETE:
        return $this->requiredDeleteFields;
        break;

      default:
        throw new main\ExtendedException(self::ERR_INVALIDOP);
        break;
    }
  }

  /**
   * Required fields for a given database operation
   * @param int $operation Table::INSERT, Table::UPDATE, Table::DELETE
   * @param string $fields if there's more than one field, separate it 
   * with ,(comma)
   * @return void
   * @throws ExtendedException
   */
  final protected function setRequiredFields($operation, $fields) {

    switch ($operation) {
      case self::INSERT:
        $fieldArray = array();
        foreach (explode(',', $fields) as $field) {
          array_push($fieldArray, trim($field));
        }
        $this->requiredInsertFields = $fieldArray;
        break;

      case self::UPDATE:
        $fieldArray = array();
        foreach (explode(',', $fields) as $field) {
          array_push($fieldArray, trim($field));
        }
        $this->requiredUpdateFields = $fieldArray;
        break;

      case self::DELETE:
        $fieldArray = array();
        foreach (explode(',', $fields) as $field) {
          array_push($fieldArray, trim($field));
        }
        $this->requiredDeleteFields = $fieldArray;
        break;

      default:
        throw new main\ExtendedException(self::ERR_INVALIDOP);
        break;
    }
  }

  /**
   * Validate required fields
   * @param int $operation Table::INSERT, Table::UPDATE, Table::DELETE
   * @return boolean
   * @throws ExtendedException
   */
  final protected function validateRequiredFields($operation) {

    switch ($operation) {
      case self::INSERT:
        foreach ($this->requiredInsertFields as $field) {
          if (!isset($this->$field)) {
            throw new main\ExtendedException(self::ERR_NULLFIELD . ': (' . $field . ')');
            return false;
          }
        }
        break;

      case self::UPDATE:
        foreach ($this->requiredUpdateFields as $field) {
          if (!isset($this->$field)) {
            throw new main\ExtendedException(self::ERR_NULLFIELD . ': (' . $field . ')');
            return false;
          }
        }
        break;

      case self::DELETE:
        foreach ($this->requiredDeleteFields as $field) {
          if (!isset($this->$field)) {
            throw new main\ExtendedException(self::ERR_NULLFIELD . ': (' . $field . ')');
            return false;
          }
        }
        break;

      default:
        throw new main\ExtendedException(self::ERR_INVALIDOP);
        break;
    }
  }

  /**
   * SQL statement
   * @return string
   */
  final public function getSqlStatement() {
    return $this->sqlStatement;
  }

  /**
   * SQL statement
   * @param string $sqlStatement
   * @param int $operation[optional] e.g.: self::INSERT, self::UPDATE,
   * self::DELETE
   * @return void
   */
  final public function setSqlStatement($sqlStatement, $operation = null) {

    if (isset($operation)) {
      $this->validateRequiredFields($operation);
    }

    $this->sqlStatement = $sqlStatement;
  }

  /**
   * Create SQL INSERT statement
   * @return boolean
   */
  final public function createInsertStatement() {

    try {
      $fieldCount = count($this->getInsertFields());
    } catch (main\ExtendedException $e) {
      echo $e->getDetailedMessage();
      exit;
    }

    $fieldsArray = array_keys($this->getInsertFields());
    $valuesArray = array_values($this->getInsertFields());


    $sql = "INSERT INTO " . $this->getTableName() . " ( ";

    $i = 0;

    foreach ($fieldsArray as $field) {
      $sql .= $field;
      if ($i + 1 < $fieldCount) {
        $sql .= ", ";
      }
      $i++;
    }

    $sql .= " ) VALUES ( ";

    $ii = 0;

    foreach ($valuesArray as $value) {
      $sql .= $value;
      if ($ii + 1 < $fieldCount) {
        $sql .= ", ";
      }
      $ii++;
    }

    $sql .= " )";

    try {
      $this->setSqlStatement($sql, self::INSERT);
      return true;
    } catch (main\ExtendedException $e) {
      echo $e->getDetailedMessage();
      exit;
    }
  }

  /**
   * Create SQL UPDATE statement
   * @param string $whereClause[optional] required if $strictUpdate is set true
   * @return boolean
   */
  final public function createUpdateStatement($whereClause = null) {

    try {
      $fieldCount = count($this->getUpdateFields());
    } catch (main\ExtendedException $e) {
      echo $e->getDetailedMessage();
      exit;
    }

    try {
      $this->validateRestriction(self::UPDATE, $whereClause);
    } catch (main\ExtendedException $e) {
      echo $e->getDetailedMessage();
      exit;
    }

    if (isset($whereClause) || !$this->getStrictUpdate()) {

      $sql = "UPDATE " . $this->getTableName() . " SET ";

      $counter = 0;

      foreach ($this->getUpdateFields() as $field) {
        $sql .= $field;
        if ($counter + 1 < $fieldCount) {
          $sql .= ", ";
        }

        $counter++;
      }

      if (isset($whereClause)) {
        if (preg_match('/WHERE/i', $whereClause)) {
          $sql .= " " . $whereClause;
        } else {
          $sql .= " WHERE " . $whereClause;
        }
      }
    }

    try {
      $this->setSqlStatement($sql, self::UPDATE);
      return true;
    } catch (main\ExtendedException $e) {
      echo $e->getDetailedMessage();
      exit;
    }
  }

  /**
   * Result of SQL statement
   * @return mysqli_result
   */
  final public function getSqlResult() {
    return $this->sqlResult;
  }

  /**
   * Result of SQL statement
   * @param mysqli_result $sqlResult
   * @return void
   */
  final public function setSqlResult($sqlResult) {
    $this->sqlResult = $sqlResult;
  }

  /**
   * Executes the SQL statement
   * @return boolean
   * @throws ExtendedException
   */
  final protected function executeQuery() {

    if (!isset($this->sqlStatement)) {
      throw new main\ExtendedException(self::ERR_NOSQLSTMT);
    }

    $result = $this->dbConn->query($this->getSqlStatement());
    if ($result) {
      $this->setSqlResult($result);
      $this->setNumRows($this->dbConn->affected_rows);
      return true;
    } else {
      throw new main\ExtendedException($this->dbConn->error);
      return false;
    }
  }

  /**
   * Number of rows of the SQL result object
   * @param int $numRows
   * @return void
   */
  final public function setNumRows($numRows) {
    $this->numRows = $numRows;
  }

  /**
   * Number of rows of the SQL result object
   * @return int
   */
  final public function getNumRows() {
    return $this->numRows;
  }

  /**
   * Number of rows of the RecordList SQL result object
   * @return int
   */
  final public function getNumRowsFromRecordList() {
    return $this->recordList->getTotalRows();
  }

  /**
   * Current row from the SQL result object
   * @return int
   */
  final public function getCurrentRow() {
    return $this->currentRow;
  }

  /**
   * Moves the pointer to the next row of the SQL result object
   * @return void
   */
  final public function incrementRow() {
    $this->currentRow++;
  }

  /**
   * Strict update operations
   * @return boolean
   */
  final public function getStrictUpdate() {
    return $this->strictUpdate;
  }

  /**
   * Strict update operations
   * @param boolean $strictUpdate
   * @return void
   */
  final public function setStrictUpdate($strictUpdate) {
    $this->strictUpdate = $strictUpdate;
  }

  /**
   * Strict delete operations
   * @return boolean
   */
  final public function getStrictDelete() {
    return $this->strictDelete;
  }

  /**
   * Strict delete operations
   * @param boolean $strictDelete
   * @return void
   */
  final public function setStrictDelete($strictDelete) {
    $this->strictDelete = $strictDelete;
  }

  /**
   * Validates restrictions for a given operation
   * @param int $operation Table::UPDATE, Table::DELETE
   * @param string $whereClause[optional]
   * @return boolean
   * @throws ExtendedException
   */
  final protected function validateRestriction($operation, $whereClause = null) {

    switch ($operation) {
      case self::UPDATE:
        if ($this->getStrictUpdate() && trim($whereClause) == '') {
          throw new main\ExtendedException(self::ERR_STRICTOP);
        } else {
          return true;
        }
        break;
      case self::DELETE:
        if ($this->getStrictDelete() && trim($whereClause) == '') {
          throw new main\ExtendedException(self::ERR_STRICTOP);
        } else {
          return true;
        }
        break;
      default:
        throw new main\ExtendedException(self::ERR_INVALIDOP);
        break;
    }
  }

  /**
   * Current Date and time
   * @param boolean $timestamp[optional]
   * @return datetime sql type format if $timestamp is set to false and int 
   * otherwise
   */
  final protected function getCurrentDate($timestamp = false) {

    $currentDate = (time() - (date('I') * 3600));

    if ($timestamp) {
      return $currentDate;
    } else {
      return date("Y-m-d H:i:s", $currentDate);
    }
  }

  /**
   * SELECT operation
   * @param string $fields[optional] Especify the fields that will be selected.
   * Use ,(comma) to distinguish them
   * Default: *(all)
   * @param string $whereClause[optional] Use this parameter for additional 
   * clauses like WHERE, AND, ORDER BY, LIMIT, ETC...
   * @return boolean
   */
  public function select($fields = '*', $whereClause = null) {

    try {
      $this->setSqlStatement("SELECT " . $fields . " FROM " . $this->getTableName() . " " . $whereClause);
      return $this->executeQuery();
    } catch (main\ExtendedException $e) {
      echo $e->getDetailedMessage();
      exit;
    }
  }

  /**
   * Create RecordList object
   * @param string $fields[optional] separeted by ,(comma). If null is given 
   * $field will be set to '*'
   * @param string $whereClause[optional] use this parameter for JOIN, WHERE,
   * AND, LIKE clauses (Parameters that filter the query result).
   * @param int $currentPage[optional] page to be presented.
   * @param int $recordsPerPage[optional] if null is given the result object
   * will contain all records and $currentPage value will be ignored
   * @return RecordList
   */
  public function createRecordList($fields = '*', $whereClause = null, $currentPage = 1, $recordsPerPage = DEFAULT_RPP) {

    $sqlStmt = "SELECT " . $fields . " FROM " . $this->getTableName() . " " . $whereClause;

    $this->recordList = new RecordList($this->dbConn, $sqlStmt, $currentPage, $recordsPerPage);

    //Stores RecordList result set into Table own sqlResult so it can be handled directly.
    $this->setSqlStatement($sqlStmt);
    $this->setSqlResult($this->recordList->getSqlResult());
    $this->setNumRows($this->dbConn->affected_rows);

    return $this->recordList;
  }

  /**
   * Last RecordList object created
   * @return RecordList or null if there's none
   */
  public function getRecordList() {
    return $this->recordList;
  }

  /**
   * DELETE operation
   * @param string $whereClause[optional] Use this parameter for additional
   * clauses like WHERE, AND, LIMIT, ETC...
   * By default, the defination of $whereClause is required, as a security
   * measure. 
   * To change it, use setStrictDelete(false) where false deactivates and true
   * activates it.
   * @return boolean
   */
  public function delete($whereClause = null) {

    try {
      $this->validateRestriction(self::DELETE, $whereClause);

      if (!preg_match('/WHERE/i', $whereClause)) {
        $whereClause = " WHERE " . $whereClause;
      }

      $this->setSqlStatement("DELETE FROM " . $this->getTableName() . " " . $whereClause, self::DELETE);
      return $this->executeQuery();
    } catch (main\ExtendedException $e) {
      echo $e->getDetailedMessage();
      exit;
    }
  }

  /**
   * INSERT operation
   * @return boolean
   */
  abstract public function insert();

  /**
   * UPDATE operation
   * @param string $whereClause[optional] Use this parameter for additional
   * clauses like WHERE, AND, LIMIT, ETC...
   * By default, the defination of $whereClause is required, as a security
   * measure. 
   * To change it, use setStrictUpdate(false) where false deactivates  and true
   * activates it.
   * @return boolean
   */
  abstract public function update($whereClause = null);

  /**
   * Populates class properties with results from the current result row and
   * moves resultset pointer to next row.
   * The class properties that represent a table column MUST be able to
   * be accessed by Table (its super class). Define them with, at least,
   * PROTECTED access level
   * @return boolean true if there's a next row and false if there isn't
   */
  public function nextRow() {

    //checks if it hasn't reach the and of the resultset
    if ($this->getCurrentRow() < $this->getNumRows()) {

      $this->sqlResult->data_seek($this->getCurrentRow());
      $row = $this->sqlResult->fetch_assoc();

      foreach ($this->getTableColumns() as $tableColumn) {

        //checks if the current table column exits as a class property 
        #if(property_exists($this, $tableColumn) && isset($row[$tableColumn])){
        if (isset($row[$tableColumn])) {
          //populates class properties with correspondent table columnn values
          $this->$tableColumn = $row[$tableColumn];
        } else {
          $this->$tableColumn = null;
        }
      }

      //moves pointer to next result row
      $this->incrementRow();
      return true;
    } else {
      //End of resultset reached
      return false;
    }
  }

}