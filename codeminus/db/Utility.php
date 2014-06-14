<?php

namespace codeminus\db;

use codeminus\main as main;

/**
 * Database Utility class
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.1
 */
class Utility {
  
  //use \codeminus\traits\Singleton;
  
  /**
   * The name of the table.
   */
  const TABLE_NAME = 'Name';
  
  /**
   * The storage engine for the table.
   */
  const TABLE_ENGINE = 'Engine';
  
  /**
   * The version number of the table's .frm file.
   */
  const TABLE_VERSION = 'Version';
  
  /**
   * The row-storage format (Fixed, Dynamic, Compressed, Redundant, Compact).
   */
  const TABLE_ROW_FORMAT = 'Row_format';
  
  /**
   * The number of rows. Some storage engines, such as MyISAM, store the exact
   * count. For other storage engines, such as InnoDB, this value is an
   * approximation, and may vary from the actual value by as much as 40 to 50%.
   * In such cases, use SELECT COUNT(*) to obtain an accurate count.
   */
  const TABLE_ROWS = 'Rows';
  
  /**
   * The average row length.
   */
  const TABLE_AVG_ROW_LENGTH = 'Avg_row_length';
  
  /**
   * The length of the data file.
   */
  const TABLE_DATA_LENGTH = 'Data_length';
  
  /**
   * The maximum length of the data file. This is the total number of bytes of
   * data that can be stored in the table, given the data pointer size used.
   */
  const TABLE_MAX_DATA_LENGTH = 'Max_data_length';
  
  /**
   * The length of the index file.
   */
  const TABLE_INDEX_LENGTH = 'Index_length';
  
  /**
   * The number of allocated but unused bytes.
   */
  const TABLE_DATA_FREE = 'Data_free';
  
  /**
   * The next AUTO_INCREMENT value.
   */
  const TABLE_AUTO_INCREMENT = 'Auto_increment';
  
  /**
   * When the table was created.
   */
  const TABLE_CREATE_TIME = 'Create_time';
  
  /**
   * When the data file was last updated. For some storage engines, this value
   * is NULL.
   */
  const TABLE_UPDATE_TIME = 'Update_time';
  
  /**
   * When the table was last checked. Not all storage engines update this time,
   * in which case the value is always NULL. 
   */
  const TABLE_CHECK_TIME = 'Check_time';
  
  /**
   * The table's character set and collation.
   */
  const TABLE_COLLATION = 'Collation';
  
  /**
   * The live checksum value (if any).
   */
  const TABLE_CHECKSUM = 'Checksum';
  
  /**
   * Extra options used with CREATE TABLE. The original options supplied when
   * CREATE TABLE is called are retained and the options reported here may
   * differ from the active table settings and options.
   */
  const TABLE_CREATE_OPTIONS = 'Create_options';
  
  /**
   * The comment used when creating the table (or information as to why MySQL
   * could not access the table information).
   */
  const TABLE_COMMENT = 'Comment';
  
  /**
   * Indicates the column name.
   */
  const COLUMN_NAME = 'name';
  
  /**
   * Indicates the column data type.
   */
  const COLUMN_TYPE = 'type';
  
  /**
   * Indicates the column data type size.
   */
  const COLUMN_SIZE = 'size';
  
  /**
   * Indicates whether NULL values can be stored in the column.
   */
  const COLUMN_NULL = 'null';
  
  /**
   * Indicates whether the column is indexed.
   */
  const COLUMN_KEY = 'key';
  
  /**
   * Indicates the default value that is assigned to the column. 
   */
  const COLUMN_DEFAULT = 'default';
  
  /**
   * Contains any additional information that is available about a given column.
   * The value is auto_increment for columns that have the AUTO_INCREMENT
   * attribute and empty otherwise.
   */
  const COLUMN_EXTRA = 'extra';

  /**
   * Tables within the connected database
   * @param bool $completeInfo [optional] if TRUE, it will return a
   * multi-dimentional array with the following structure:<br/>
   * $tables[index]['Name']<br/>
   * $tables[index]['Engine']<br/>
   * $tables[index]['Version']<br/>
   * $tables[index]['Row_format']<br/>
   * $tables[index]['Rows']<br/>
   * $tables[index]['Avg_row_length']<br/>
   * $tables[index]['Data_length']<br/>
   * $tables[index]['Max_data_length']<br/>
   * $tables[index]['Index_length']<br/>
   * $tables[index]['Data_free']<br/>
   * $tables[index]['Auto_increment']<br/>
   * $tables[index]['Create_time']<br/>
   * $tables[index]['Update_time']<br/>
   * $tables[index]['Check_time']<br/>
   * $tables[index]['Collation']<br/>
   * $tables[index]['Checksum']<br/>
   * $tables[index]['Create_options']<br/>
   * $tables[index]['Comment']
   * @param \codeminus\db\Connection $dbConn a database connection
   * @return array Returns and array containing all tables within the database
   */
  public static function getTables($completeInfo = true, Connection $dbConn = null) {
    if (!($dbConn instanceof Connection)) {
      $dbConn = Connection::getInstance();
    }
    $tables = array();
    if ($completeInfo) {
      $sql = 'SHOW TABLE STATUS FROM ' . $dbConn->getDatabase();
      $result = $dbConn->query($sql);
      while ($table = $result->fetch_assoc()) {
        $tables[] = $table;
      }
    } else {
      $result = $dbConn->query('SHOW TABLES');
      while ($table = $result->fetch_row()) {
        $tables[] = $table[0];
      }
    }
    return $tables;
  }

  /**
   * Table information
   * @param string $tableName The name of the table
   * @param string $info [optional] one of the \codeminus\db\Utility constants
   * for table information. If null is given, it will return an array containing
   * all the informations about the table
   * @param \codeminus\db\Connection $dbConn [optional] A database connection.
   * If null is given, it will attempt to get a Connection instance
   * automatically
   * @return mixed String if $info is given and array otherwise
   */
  public static function getTableInfo($tableName, $info = null, Connection $dbConn = null) {
    if (!($dbConn instanceof Connection)) {
      $dbConn = Connection::getInstance();
    }
    $sql = "SHOW TABLE STATUS FROM " . $dbConn->getDatabase() . " LIKE '$tableName'";
    $result = $dbConn->query($sql);
    $fetch = $result->fetch_assoc();
    if (isset($info)) {
      return $fetch[$info];
    } else {
      return $fetch;
    }
  }

  /**
   * Returns the columns of a given table
   * @param string $tableName the name of the table
   * @param bool $completeInfo [optional] if TRUE, it will return a
   * multi-dimentional array with the following structure:
   * $columns[0]['name']<br/>
   * $columns[0]['type']<br/>
   * $columns[0]['size']<br/>
   * $columns[0]['null']<br/>
   * $columns[0]['key']<br/>
   * $columns[0]['default']<br/>
   * $columns[0]['extra']<br/>
   * If FALSE it will return an array containing only the columns names
   * @param Connection $dbConn [optional] a database connection. If null is
   * given, it will attempt to get a Connection instance automatically
   * @param string $tableField [optional] If a table field is given it will
   * return informations for only this field.
   * @return array An array with the following structure:<br/>
   * 
   * @throws codeminus\main\ExtendedException
   */
  public static function getTableColumns($tableName, $completeInfo = true, Connection $dbConn = null, $tableColumnName = null) {
    if (!($dbConn instanceof Connection)) {
      $dbConn = Connection::getInstance();
    }
    if (empty($tableName)) {
      throw new main\ExtendedException('No table name was given', E_ERROR);
    }
    $result = $dbConn->query("DESCRIBE " . $tableName . " " . $tableColumnName);
    if (!$result) {
      throw new main\ExtendedException($dbConn->error, E_ERROR);
    }
    $tableColumnsArray = array();
    while ($row = $result->fetch_assoc()) {
      if ($completeInfo) {
        $typeArray = explode(' ', $row['Type']);
        $typeAndSize = explode('(', $typeArray[0]);
        $type = $typeAndSize[0];
        if (count($typeAndSize) > 1) {
          $size = str_replace(')', '', $typeAndSize[1]);
        } else {
          $size = null;
        }
        if ($row['Null'] == 'YES') {
          $null = true;
        } else {
          $null = false;
        }
        $tableColumn['name'] = $row['Field'];
        $tableColumn['type'] = trim($type);
        $tableColumn['size'] = $size;
        $tableColumn['null'] = $null;
        $tableColumn['key'] = $row['Key'];
        $tableColumn['default'] = $row['Default'];
        $tableColumn['extra'] = $row['Extra'];
        if (!isset($tableColumnName)) {
          array_push($tableColumnsArray, $tableColumn);
        } else {
          $tableColumnsArray = $tableColumn;
        }
      } else {
        if (!isset($tableColumnName)) {
          array_push($tableColumnsArray, $row['Field']);
        } else {
          $tableColumnsArray = $row['Field'];
        }
      }
    }
    return $tableColumnsArray;
  }

  /**
   * The informations of a given table column
   * @param string $tableName The database table name
   * @param string $tableColumn The database table column name
   * @param string $info [optional] one of the \codeminus\db\Utility constants
   * for table column information. If null is given, it will return an array
   * containing all the informations about the table column
   * @param \codeminus\db\Connection $dbConn [optional] A database connection.
   * If null is given, it will attempt to get a Connection instance
   * automatically
   * @return mixed String if $info is given and array otherwise
   */
  public static function getTableColumnInfo($tableName, $tableColumn, $info = null, Connection $dbConn = null) {
    $tableColumns = self::getTableColumns($tableName, true, $dbConn, $tableColumn);
    if (!isset($info)) {
      return $tableColumns;
    } else {
      return $tableColumns[$info];
    }
  }

  /**
   * Mysql field types
   * @param bool $equivalentPhpTypes [optional] if TRUE, it will return an
   * associative array where the keys will correspond to the mysql data type and
   * the values will correspond to the php equivalent types
   * @return array An array with all mysql field types up to MySQL 5.1 
   */
  public static function getMysqlFieldTypes($equivalentPhpTypes = true) {
    if ($equivalentPhpTypes) {
      return array(
          //boolean types
          'bool' => 'bool',
          'boolean' => 'bool',
          //Numeric types
          'serial' => 'int',
          'bit' => 'int',
          'tinyint' => 'int',
          'smallint' => 'int',
          'mediumint' => 'int',
          'int' => 'int',
          'integer' => 'int',
          'bigint' => 'int',
          'decimal' => 'float',
          'dec' => 'float',
          'numeric' => 'float',
          'fixed' => 'float',
          'float' => 'float',
          'double' => 'float',
          'real' => 'float',
          //Date and time types
          'date' => 'string',
          'datetime' => 'string',
          'timestamp' => 'string',
          'time' => 'string',
          'year' => 'int',
          //String types
          'char' => 'string',
          'varchar' => 'string',
          'binary' => 'string',
          'varbinary' => 'string',
          'tinyblob' => 'string',
          'blob' => 'string',
          'mediumblob' => 'string',
          'longblob' => 'string',
          'tinytext' => 'string',
          'text' => 'string',
          'mediumtext' => 'string',
          'longtext' => 'string',
          'enum' => 'string',
          'set' => 'string'
      );
    } else {
      return array(
          //boolean types
          'bool',
          'boolean',
          //Numeric types
          'serial',
          'bit',
          'tinyint',
          'smallint',
          'mediumint',
          'int',
          'integer',
          'bigint',
          'decimal',
          'dec',
          'numeric',
          'fixed',
          'float',
          'double',
          'real',
          //Date and time types
          'date',
          'datetime',
          'timestamp',
          'time',
          'year',
          //String types
          'char',
          'varchar',
          'binary',
          'varbinary',
          'tinyblob',
          'blob',
          'mediumblob',
          'longblob',
          'tinytext',
          'text',
          'mediumtext',
          'longtext',
          'enum',
          'set'
      );
    }
  }

}