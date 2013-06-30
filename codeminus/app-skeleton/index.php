<?php
require_once ('app/configs/init.php');

use \codeminus\db as db;

class User extends db\Table{
    
    public function __construct(){
        parent::__construct('user');
    }
    
    public function insert(){}
    public function update($where = null){
        
        $this->addUpdateField('name','um nome');
        $this->createUpdateStatement($where);
        
    }
    
}