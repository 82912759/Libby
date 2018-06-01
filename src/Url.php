<?php 
/**
 * 
 */

namespace Libby;

class Url {
    
    protected $scheme;
    protected $host;
    protected $path;
    protected $queryData = [];
    
    /**
     * 
     */
    public function __construct ( $url = null ) {
        
        if ($url !== null) {
            
            $this->setUrl($url);
        }
    }
    
    
    /**
     * 
     */
    public function __toString ( ) {
        
        return $this->toString();        
    }
    
    
    //
    // Base methods
    //
    /**
     *
     */
    public function setQuery ( $query ) {
        
        if (is_array($query)) {
            
            foreach ($query as $key => $value) {
                $this->queryData[$key] = $value;
            }
        }
        else {
            parse_str($query, $this->queryData);
        }
        
        return $this;
    }
    
    
    /**
     * 
     */
    public function setUrl ( $url ) {
        
        $data = parse_url($url);
        
        $this->scheme = (!empty($data['scheme']) ? $data['scheme'] : null);
        $this->host = $data['host'];
        $this->path = $data['path'];
        
        if (!empty($data['query'])) {
            parse_str($data['query'], $this->queryData);
        }
        
        return $this;
    }
    
    
    /**
     * 
     */
    public function toString ( ) {
        
        $url = (!empty($this->scheme) ? $this->scheme . '://' : '//') .  $this->host . $this->path;
        
        
        if (!empty($this->queryData)) {
            $url .= '?' . http_build_query($this->queryData);
        }
        
        
        return $url;
    }
}