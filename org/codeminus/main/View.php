<?php
namespace org\codeminus\main;

/**
 * Base view object
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 1.0
 */
class View {
    
    private static $TITLE;
    
    /**
     * Base view
     * @return View
     */
    public function __construct() {
        self::$TITLE = VIEW_DEFAULT_TITLE;
    }
    
    /**
     * View title
     * @param string $title
     * @return void
     */
    public function setTitle($title){
        self::$TITLE = $title;
    }
    
    /**
     * View title
     * @return string
     */
    public static function getTitle(){
        if(self::$TITLE){
            return self::$TITLE;
        //if none is set it creates on based on controller name and controller called method name
        }else{
            return Router::$CONTROLLER_NAME.' :: '.Router::$CONTROLLER_METHOD_NAME;
                    
        }
    }
    
    /**
     * Includes header file as defined on VIEW_DEFAULT_HEADER
     * @return void
     * @throws ExtException
     */
    public static function includeHeader(){
        $path = VIEW_PATH.VIEW_DEFAULT_HEADER;
        if(file_exists($path)){
            require_once $path;
        }else{
            throw new ExtException('<b>Error: </b> requested header file not found on <b>'.$path.'</b>');
        }
    }
    
    /**
     * Includes footer file as defined on VIEW_DEFAULT_FOOTER
     * @return void
     * @throws ExtException
     */
    public static function includeFooter(){
        $path = VIEW_PATH.VIEW_DEFAULT_FOOTER;
        if(file_exists($path)){
            require_once $path;
        }else{
            throw new ExtException('<b>Error: </b> requested footer file not found on <b>'.$path.'</b>');
        }
    }
    
    /**
     * Renders the view
     * @param string $view
     * @param boolean $autoIncludes if set to false it wont require the DEFAULT_INCLUDE_HEADER and the DEFAULT_INCLUDE_FOOTER;
     * @return void
     * @throws ExtException
     */
    public function render($view, $autoIncludes = true){
        $filepath = VIEW_PATH.'/'.$view.'.php';
        
        if($autoIncludes){            
            self::includeHeader();
        }
        
        if(file_exists($filepath)){
            require_once $filepath;
        }else{
            throw new ExtException('<b>Error:</b> View not found in '.$filepath);
        }
        
        if($autoIncludes){
            self::includeFooter();
        }
        
    }
    
}