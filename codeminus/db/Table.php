<?php

namespace codeminus\db;

use codeminus\main as main;

/**
 * Database Table abstract model
 * Extend this class on all classes that represent a table on a database
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.2
 */
abstract class Table {

  //Database connection object
  private $dbConn;
  //Database properties
  private $tableName;
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

  //Database operations
  const INSERT = 0;
  const UPDATE = 1;
  const DELETE = 2;

  //Error messages    
  const ERR_NOSQLSTMT = 'SQL statement not defined';
  const ERR_INVALIDSQL = 'Invalid SQL statement';
  const ERR_NULLFIELD = 'Required table field not set';
  const ERR_NOFIELDS = 'No table fields defined';
  const ERR_INVALIDOP = 'Invalid database operation';
  const ERR_STRICTOP = 'Strict database operation. Where clause not set';

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
  public function getDbConn() {
    return $this->dbConn;
  }

  /**
   * Database connection link
   * @param Connection $dbConn
   * @return void
   */
  public function setDbConn($dbConn) {
    $this->dbConn = $dbConn;
  }

  /**
   * Database connection error message
   * @return string
   */
  public function getDbConnError() {
    return $this->dbConn->error;
  }

  /**
   * Database table name
   * @return string
   */
  public function getTableName() {
    return $this->tableName;
  }

  /**
   * Database table name
   * @param string $tableName
   * @return void
   */
  protected function setTableName($tableName) {
    $this->tableName = $tableName;
  }

  /**
   * Database table columns
   * @param bool $completeInfo[optional] if TRUE, it will return a
   * multi-dimentional array like the following:
   * $columns[0]['name']<br/>
   * $columns[0]['type']<br/>
   * $columns[0]['size']<br/>
   * $columns[0]['null']<br/>
   * $columns[0]['key']<br/>
   * $columns[0]['default']<br/>
   * $columns[0]['extra']<br/>
   * If FALSE it will return an array containing only the columns names
   * @return array containing all field names
   */
  public function getTableColumns($completeInfo = true) {
    return Utility::getTableColumns($this->tableName, $completeInfo);
  }

  /**
   * Database table column information
   * @param string $column The column name
   * @param string $info The information to be returned. If it is not given an
   * array with all informations about the $column will be returned. Refer to
   * \codeminus\db\Utility class for a list of constants prefixed with
   * COLUMN_ to be used as the info
   * @return mixed Depend on the type of the information requested. 
   */
  public function getTableColumnInfo($column, $info = null) {
    return Utility::getTableColumnInfo($this->getTableName(), $column, $info);
  }

  /**
   * Table fields and values to INSERT
   * @return array
   * @throws codeminus\main\ExtendedException
   */
  public function getInsertFields() {
    if (count($this->insertFields) == 0) {
      throw new main\ExtendedException(self::ERR_NOFIELDS, main\ExtendedException::E_ERROR);
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
  protected function setInsertFields($insertFields) {
    $this->insertFields = $insertFields;
  }

  /**
   * Adds a table field and its value to be used in the INSERT statement
   * @param string $field database table field name
   * @param string $value database table field value
   * @param bool $quoted[optional] adds single quote to $value if set to true
   * @example addInsertField('field', 'value') will add "field='value'" to the
   * INSERT statement.
   * @return void
   */
  protected function addInsertField($field, $value, $quoted = true) {
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
   * @throws codeminus\main\ExtendedException
   */
  public function getUpdateFields() {
    if (count($this->updateFields) == 0) {
      throw new main\ExtendedException(self::ERR_NOFIELDS, main\ExtendedException::E_ERROR);
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
  protected function setUpdateFields($updateFields) {
    $this->updateFields = $updateFields;
  }

  /**
   * Adds a table field and its value to be used in the UPDATE statement
   * @param string $field database table field name
   * @param string $value database table field value
   * @param bool $quoted[optional] adds single quote to $value if set to true
   * @return void
   * @example addUpdateField('field', 'value') will add "field='value'" to the
   * UPDATE statement.
   */
  protected function addUpdateField($field, $value, $quoted = true) {
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
   * @throws codeminus\main\ExtendedException
   */
  public function getRequiredFields($operation) {
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
        throw new main\ExtendedException(self::ERR_INVALIDOP, main\ExtendedException::E_ERROR);
        break;
    }
  }

  /**
   * Required fields for a given database operation
   * @param int $operation Table::INSERT, Table::UPDATE, Table::DELETE
   * @param string $fields if there's more than one field, separate it 
   * with ,(comma)
   * @return void
   * @throws codeminus\main\ExtendedException
   */
  protected function setRequiredFields($operation, $fields) {
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
        throw new main\ExtendedException(self::ERR_INVALIDOP, main\ExtendedException::E_ERROR);
        break;
    }
  }

  /**
   * Validate required fields
   * @param int $operation Table::INSERT, Table::UPDATE, Table::DELETE
   * @return bool
   * @throws codeminus\main\ExtendedException
   */
  protected function validateRequiredFields($operation) {
    switch ($operation) {
      case self::INSERT:
        foreach ($this->requiredInsertFields as $field) {
          if (!isset($this->$field)) {
            throw new main\ExtendedException(self::ERR_NULLFIELD . ': (' . $field . ')', main\ExtendedException::E_ERROR);
            return false;
          }
        }
        break;
      case self::UPDATE:
        foreach ($this->requiredUpdateFields as $field) {
          if (!isset($this->$field)) {
            throw new main\ExtendedException(self::ERR_NULLFIELD . ': (' . $field . ')', main\ExtendedException::E_ERROR);
            return false;
          }
        }
        break;
      case self::DELETE:
        foreach ($this->requiredDeleteFields as $field) {
          if (!isset($this->$field)) {
            throw new main\ExtendedException(self::ERR_NULLFIELD . ': (' . $field . ')', main\ExtendedException::E_ERROR);
            return false;
          }
        }
        break;
      default:
        throw new main\ExtendedException(self::ERR_INVALIDOP, main\ExtendedException::E_ERROR);
        break;
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
   * @param string $sqlStatement
   * @param int $operation[optional] e.g.: self::INSERT, self::UPDATE,
   * self::DELETE
   * @return void
   */
  public function setSqlStatement($sqlStatement, $operation = null) {
    if (isset($operation)) {
      $this->validateRequiredFields($operation);
    }
    $this->sqlStatement = $sqlStatement;
  }

  /**
   * Create SQL INSERT statement
   * @return bool
   */
  public function createInsertStatement() {
    try {
      $fieldCount = count($this->getInsertFields());
    } catch (main\ExtendedException $e) {
      echo $e->getFormattedMessage();
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
      echo $e->getFormattedMessage();
      exit;
    }
  }

  /**
   * Create SQL UPDATE statement
   * @param string $whereClause[optional] required if $strictUpdate is set true
   * @return bool
   */
  public function createUpdateStatement($whereClause = null) {
    try {
      $fieldCount = count($this->getUpdateFields());
    } catch (main\ExtendedException $e) {
      echo $e->getFormattedMessage();
      exit;
    }
    try {
      $this->validateRestriction(self::UPDATE, $whereClause);
    } catch (main\ExtendedException $e) {
      echo $e->getFormattedMessage();
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
      echo $e->getFormattedMessage();
      exit;
    }
  }

  /**
   * Result of SQL statement
   * @return \mysqli_result
   */
  public function getSqlResult() {
    return $this->sqlResult;
  }

  /**
   * Result of SQL statement
   * @param \mysqli_result $sqlResult
   * @return void
   */
  public function setSqlResult($sqlResult) {
    $this->sqlResult = $sqlResult;
  }

  /**
   * Executes the SQL statement
   * @return bool
   * @throws codeminus\main\ExtendedException
   */
  protected function executeQuery() {
    if (!isset($this->sqlStatement)) {
      throw new main\ExtendedException(self::ERR_NOSQLSTMT, main\ExtendedException::E_ERROR);
    }
    $result = $this->dbConn->query($this->getSqlStatement());
    if ($result) {
      $this->setSqlResult($result);
      $this->setNumRows($this->dbConn->affected_rows);
      return true;
    } else {
      throw new main\ExtendedException($this->dbConn->error, main\ExtendedException::E_ERROR);
      return false;
    }
  }

  /**
   * Number of rows of the SQL result object
   * @param int $numRows
   * @return void
   */
  public function setNumRows($numRows) {
    $this->numRows = $numRows;
  }

  /**
   * Number of rows of the SQL result object
   * @return int
   */
  public function getNumRows() {
    return $this->numRows;
  }

  /**
   * Number of rows of the RecordList SQL result object
   * @return int
   */
  public function getNumRowsFromRecordList() {
    return $this->recordList->getTotalRows();
  }

  /**
   * Current row from the SQL result object
   * @return int
   */
  public function getCurrentRow() {
    return $this->currentRow;
  }

  /**
   * Moves the pointer to the next row of the SQL result object
   * @return void
   */
  public function incrementRow() {
    $this->currentRow++;
  }

  /**
   * Strict update operations
   * @return bool
   */
  public function getStrictUpdate() {
    return $this->strictUpdate;
  }

  /**
   * Strict update operations
   * @param bool $strictUpdate
   * @return void
   */
  public function setStrictUpdate($strictUpdate) {
    $this->strictUpdate = $strictUpdate;
  }

  /**
   * Strict delete operations
   * @return bool
   */
  public function getStrictDelete() {
    return $this->strictDelete;
  }

  /**
   * Strict delete operations
   * @param bool $strictDelete
   * @return void
   */
  public function setStrictDelete($strictDelete) {
    $this->strictDelete = $strictDelete;
  }

  /**
   * Validates restrictions for a given operation
   * @param int $operation Table::UPDATE, Table::DELETE
   * @param string $whereClause[optional]
   * @return bool
   * @throws codeminus\main\ExtendedException
   */
  protected function validateRestriction($operation, $whereClause = null) {
    switch ($operation) {
      case self::UPDATE:
        if ($this->getStrictUpdate() && trim($whereClause) == '') {
          throw new main\ExtendedException(self::ERR_STRICTOP, main\ExtendedException::E_ERROR);
        } else {
          return true;
        }
        break;
      case self::DELETE:
        if ($this->getStrictDelete() && trim($whereClause) == '') {
          throw new main\ExtendedException(self::ERR_STRICTOP, main\ExtendedException::E_ERROR);
        } else {
          return true;
        }
        break;
      default:
        throw new main\ExtendedException(self::ERR_INVALIDOP, main\ExtendedException::E_ERROR);
        break;
    }
  }

  /**
   * Current Date and time
   * @param bool $timestamp[optional]
   * @return string $timestamp is set to false and int 
   * otherwise
   */
  protected function getCurrentDate($timestamp = false) {
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
   * @return bool
   */
  public function select($fields = '*', $whereClause = null) {
    try {
      $this->setSqlStatement("SELECT " . $fields . " FROM " . $this->getTableName() . " " . $whereClause);
      return $this->executeQuery();
    } catch (main\ExtendedException $e) {
      echo $e->getFormattedMessage();
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
   * @return bool
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
      echo $e->getFormattedMessage();
      exit;
    }
  }

  /**
   * INSERT operation
   * @return bool
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
   * @return bool
   */
  abstract public function update($whereClause = null);

  /**
   * Populates class properties with results from the current result row and
   * moves resultset pointer to next row.
   * The class properties that represent a table column MUST be able to
   * be accessed by Table (its super class). Define them with, at least,
   * PROTECTED access level
   * @return bool TRUE if there's a next row or FALSE if there isn't
   */
  public function nextRow() {
    //checks if it hasn't reach the end of the resultset
    if ($this->getCurrentRow() < $this->getNumRows()) {
      $this->sqlResult->data_seek($this->getCurrentRow());
      $row = $this->sqlResult->fetch_assoc();
      foreach ($this->getTableColumns(false) as $tableColumn) {
        //checks if the current table column exits as a class property 
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