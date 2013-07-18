<?php

namespace codeminus\db;

/**
 * TableClass 
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.2
 */
class TableClass {

  private $tableName;
  private $tableColumns;
  private $namespace;
  private $code;
  private $dataTypes;

  /**
   * Generates a class definition for an existing database table.
   * The class will always inherit the abstract class \codeminus\db\Table.
   * Review all generated code as it only does basic assumptions
   * @param Connection $dbConn an instance of a database
   * connection
   * @param string $tableName the database table you want to implement a class
   * from
   * @param string $namespace the class package
   * @return TableClass
   */
  public function __construct(Connection $dbConn, $tableName, $namespace = null) {
    $this->tableColumns = Utility::getTableColumns($tableName, $dbConn);
    $this->setTableName($tableName);
    $this->setNamespace($namespace);
    $this->dataTypes = Utility::getMysqlFieldTypes();
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
  public function setTableName($tableName) {
    $this->tableName = $tableName;
  }

  /**
   * Class namespace
   * @return string
   */
  public function getNamespace() {
    return $this->namespace;
  }

  /**
   * Class namespace
   * @param string $namespace
   * @return void
   */
  public function setNamespace($namespace) {
    $this->namespace = $namespace;
  }

  /**
   * Creates the table classe and stores it into $this->code.
   * Use $this->getCode() to get its content
   * @return void
   */
  public function create() {

    //namespace
    (isset($this->namespace) && $this->namespace != "") ? $namespace = 'namespace ' . $this->getNamespace() . ';' : $namespace = '';

    //class name
    $className = ucfirst($this->getTableName());

    //attributes declaration
    $attrDeclaration = '';
    $methodDeclaration = '';

    foreach ($this->tableColumns as $column) {
      $attrDeclaration .= '
  protected $' . $column['name'] . ';';

      $getMethod = 'get' . ucfirst($column['name']) . '()';
      $setMethod = 'set' . ucfirst($column['name']) . '($' . $column['name'] . ')';

      $columnPhrase = strtolower(preg_replace('/([A-Z])/', ' $1', $column['name']));

      //getters and setters
      $methodDeclaration .= '
  /**
   * ' . $className . ' ' . $columnPhrase . '
   * @return ' . $this->dataTypes[$column['type']] . '
   */
  public function ' . $getMethod . ' {
    return $this->' . $column['name'] . ';
  }

  /**
   * ' . $className . ' ' . $columnPhrase . '
   * @param ' . $this->dataTypes[$column['type']] . ' $' . $column['name'] . '
   * @return void
   */
  public function ' . $setMethod . ' {
    $this->' . $column['name'] . ' = $' . $column['name'] . ';
  }
';
    }

    //insert method
    $methodDeclaration .= '
  public function insert() {
';

    foreach ($this->tableColumns as $column) {

      if ($column['extra'] == "") {
        $methodDeclaration .='    $this->addInsertField(\'' . $column['name'] . '\', $this->get' . ucfirst($column['name']) . '());
';
      }
    }
    $methodDeclaration .='
    $this->createInsertStatement();
    return $this->executeQuery();
  }';

    //update method
    $methodDeclaration .= '

  public function update($whereClause = null) {
';

    foreach ($this->tableColumns as $column) {

      if ($column['extra'] == "") {
        $methodDeclaration .='    $this->addUpdateField(\'' . $column['name'] . '\', $this->get' . ucfirst($column['name']) . '());
';
      }
    }

    //setting default update where clause
    foreach ($this->tableColumns as $column) {
      if ($column['key'] == "PRI") {
        $methodDeclaration .='
    if ($whereClause == null) {
      $whereClause = \'where ' . $column['name'] . ' = \' . $this->get' . ucfirst($column['name']) . '();
    }';
        break;
      }
    }

    $methodDeclaration .='

    $this->createUpdateStatement($whereClause);
    return $this->executeQuery();
  }';

    //classFile
    $cf = '
' . $namespace . '

use codeminus\db as db;

/**
 * Description of ' . $className . '
 * @author 
 */
class ' . $className . ' extends db\Table { 
' . $attrDeclaration . '  

  /**
   * 
   * @return ' . $className . '
   */
  public function __construct() {
    parent::__construct(\'' . $this->getTableName() . '\');
  }   
' . $methodDeclaration . '

}
';

    $this->setCode($cf);
  }

  public function getCode() {
    return $this->code;
  }

  private function setCode($code) {
    $this->code = $code;
  }

  public function save($destination){
    
  }
  
}