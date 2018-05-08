<?php 
/**
 * 
 */

namespace Libby\Color;

class Color {
    
    protected $r;
    protected $g;
    protected $b;
    
    
    /**
     * 
     */
    public function __construct ( $r, $g, $b ) {
        
        $this->r = $r;
        $this->g = $g;
        $this->b = $b;
    }
    
    
    //
    // Base methods
    //
    /**
     * 
     */
    public function toHex ( ) {
        
        // Convert rgb back to hex
        $rgbColorAsHex = '#' . 
            str_pad(dechex($this->r), 2, "0", STR_PAD_LEFT) .
            str_pad(dechex($this->g), 2, "0", STR_PAD_LEFT) .
            str_pad(dechex($this->b), 2, "0", STR_PAD_LEFT);
        
        return $rgbColorAsHex;
    }
    
    
    //
    // Static methods
    //
    /**
     * 
     */
    public static function fromRGB ( $r, $g, $b ) {
        
        return new self($r, $g, $b);
    }
}