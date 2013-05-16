<?php
use org\codeminus\main as main;

##################################################
# CHANGE THIS CONTROLLER ACCORDING TO YOUR NEEDS #
##################################################

class Error extends main\Controller{
    
    public function __construct() {
        parent::__construct();
        $this->view->setTitle('Error');
    }
    
    public function index() {
        $this->view->render('error/pageNotFound');
    }

}