<?php 
/**
 * 
 */

namespace Libby\Http;

class Response {
    
    protected $body;
    
    /**
     * 
     */
    public function __construct ( $headers, $body = null ) {
        
        $this->body = $body;
    }
    
    
    /**
     * 
     */
    public function getBody ( ) {
        
        return $this->body;
    }
}