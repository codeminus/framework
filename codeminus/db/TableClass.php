<?php

namespace codeminus\db;

use codeminus\main as main;

/**
 * TableClass 
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.1
 */
class TableClass {

  private $tableName;
  private $tableColumns;
  private $namespace;
  private $code;

  /**
   * Generates a class definition for an existing database table.
   * The class will always inherit the abstract class \codeminus\db\Table.
   * Review all generated code as it only does basic assumptions
   * @param \codeminus\db\Connection $dbConn an instance of a database
   * connection
   * @param string $tableName the database table you want to implement a class
   * from
   * @param string $namespace the class package
   * @return TableClass
   */
  public function __construct(codeminus\db\Connection $dbConn, $tableName, $namespace = null) {
    $this->tableColumns = self::getTableColumns($dbConn, $tableName);
    $this->setTableName($tableName);
    $this->setNamespace($namespace);
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
   * 
   * @param \codeminus\db\Connection $dbConn
   * @param string $tableName
   * @return array with the following structure:
   * $columns[0]['name'],
   * $columns[0]['type'],
   * $columns[0]['size'],
   * $columns[0]['null'],
   * $columns[0]['key'],
   * $columns[0]['default'],
   * $columns[0]['extra']
   * @throws codeminus\main\ExtendedException
   */
  public static function getTableColumns(codeminus\db\Connection $dbConn, $tableName) {
    
    if(empty($tableName)){
      throw new main\ExtendedException('No table name was given', main\ExtendedException::E_ERROR);
    }
    
    $result = $dbConn->query("DESCRIBE " . $tableName);

    if (!$result) {
      throw new main\ExtendedException($dbConn->error, main\ExtendedException::E_ERROR);
    }

    $tableColumnsArray = array();

    while ($row = $result->fetch_assoc()) {

      $typeArray = explode(' ', $row['Type']);

      $typeAndSize = explode('(', $typeArray[0]);
      $type = $typeAndSize[0];

      (count($typeAndSize) > 1) ? $size = str_replace(')', '', $typeAndSize[1]) : $size = null;

      ($row['Null'] == 'YES') ? $null = true : $null = false;

      $tableColumn['name'] = $row['Field'];
      $tableColumn['type'] = trim($type);
      $tableColumn['size'] = $size;
      $tableColumn['null'] = $null;
      $tableColumn['key'] = $row['Key'];
      $tableColumn['default'] = $row['Default'];
      $tableColumn['extra'] = $row['Extra'];

      array_push($tableColumnsArray, $tableColumn);
    }

    return $tableColumnsArray;
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
     * @return ' . $column['type'] . '
     */
    public function ' . $getMethod . ' {
        return $this->' . $column['name'] . ';
    }
    
    /**
     * ' . $className . ' ' . $columnPhrase . '
     * @param ' . $column['type'] . ' $' . $column['name'] . '
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
        $methodDeclaration .='        $this->addInsertField(\'' . $column['name'] . '\', $this->get' . ucfirst($column['name']) . '());
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
        $methodDeclaration .='        $this->addUpdateField(\'' . $column['name'] . '\', $this->get' . ucfirst($column['name']) . '());
';
      }
    }

    //setting default update where clause
    foreach ($this->tableColumns as $column) {
      if ($column['key'] == "PRI") {
        $methodDeclaration .='
        ($whereClause == null) ? $whereClause = \'where ' . $column['name'] . '=\' . $this->get' . ucfirst($column['name']) . '() : null;';
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

use \codeminus\db as db;

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

  public function setCode($code) {
    $this->code = $code;
  }

  /*
    public function save($filepath, $overwriteExistent = false) {

    $className = ucfirst($this->getTableName());

    $filepath = $filePath . '/' . $className . '.php';
    //if the file doesnt exists or $overwriteExistent == true
    if (!file_exists($filepath) || $overwriteExistent) {
    //if file created if with success
    if (file_put_contents($filePath, $this->getCode())) {
    echo '<p class="info">' . $filePath . ' file created. </p>';
    }
    } else {
    echo '<p class="warning">' . $filePath . ' file NOT created. File already exists </p>';
    }
    } */
}