<?php
namespace org\codeminus\db\pdo;

/**
 * Database connection
 * @author Wilson Santos
 * @version 0.1a
 */
class Connection extends \PDO{    
    
    private static $INSTANCE;
    
    /**
     * Opens connection with a database server
     * Default values specified on org\codeminus\main\init.php will be used when
     * an optional parameter isn't set.
     * @param string $dsn[optional]
     * @param string $username[optional]
     * @param string $passwd[optional]
     * @param array $options[optional]
     * @return Connection
     */
    public function __construct($dsn = \DB_DSN, $username = \DB_USER, $passwd = \DB_PASS, $options = null) {
        
        parent::__construct($dsn, $username, $passwd, $options);
        $this->setInstance();
    }
    
    /**
     * Clear the current DBConnection static instance, setting it to null
     */
    public static function clearLastInstance(){
        self::$INSTANCE = null;
    }
    
    /**
     * Set current instance of DBConnection to a static object
     * @return void
     */
    private function setInstance(){
        self::$INSTANCE = $this;
    }
    
    /**
     * Singleton implementation
     * Use this method to avoid multiple unnecessary connections to the same database
     * @return the last created instance of DBConnection
     */
    public static function getInstance(){
        
        self::$INSTANCE;
        
        if(!isset(self::$INSTANCE)){
            $class = __CLASS__;
            self::$INSTANCE = new $class;
        }
        
        return self::$INSTANCE;
        
    }
    
}

?>
