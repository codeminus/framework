<?php
use org\codeminus\main as main;

##################################################
# CHANGE THIS CONTROLLER ACCORDING TO YOUR NEEDS #
##################################################

class Index extends main\Controller{
    
    function __construct() {
        parent::__construct();        
        $this->view->setTitle('Framework Installed!');
    }    
    
    public function index() {
        $this->view->render('index/index');
    }
    
}