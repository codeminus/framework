<?php
namespace org\codeminus\util;
/**
 *
 * @author Wilson Santos <wilson@codeminus.org>
 * @version 0.1b
 */
class HTML {
    
    /**
     * Create image tag
     * @param string $imageSource src parameter
     * @param string $alt alt img parameter
     * @param string $parameters
     * @return string
     */
    public static function image($source, $alt = '', $parameters = ''){        
        return '<img src="'.$source.'" alt="'.$alt.'" '.$parameters.' />';
    }
    
    /**
     * Create anchor tag
     * @param string $linkSource href parameter
     * @param string $linkContent innerHTML parameter
     * @param string $title title parameter
     * @param string $parameters
     * @return string
     */
    public static function link($source, $linkContent = '', $title = '', $parameters = ''){
        return '<a href="'.$source.'" title="'.$title.'" '.$parameters.'>'.$linkContent.'</a>';
    }
    
}