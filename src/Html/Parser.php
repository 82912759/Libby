<?php 
/**
 * 
 */

namespace Libby\Html;

class Parser {
    
    protected $html;
    
    /**
     * Set html context
     */    
    public function setHtml ( $html ) {
        
        $this->html = $html;
    }
}