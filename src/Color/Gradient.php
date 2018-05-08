<?php 
/**
 * 
 */

namespace Libby\Color;

class Gradient {
    
    protected $colorBreaks = [];
    
    /**
     * 
     */
    public function __construct ( array $colorBreaks = null ) {
        
        $this->colorBreaks = $colorBreaks;        
    }
    
    
    //
    // Base methods
    //
    function getColor ( $step, $steps ) {
                
        if ($step > $steps) {
            $step = $steps;
        }
        
        $value = $step;
        $min = 1;
        $max = $steps;
    
        // Normalize min-max range to [0, positive_value]
        $max -= $min;
        $value -= $min;
        $min = 0;
    
        // Calculate distance from min to max in [0,1]
        $distFromMin = $value / $max;
    
        // Define start and end color
        if (count($this->colorBreaks) == 2) {
            $startColor = $this->colorBreaks[0];
            $endColor = $this->colorBreaks[1];
        } else if (count($this->colorBreaks) > 2) {
            $startColor = $this->colorBreaks[floor($distFromMin * (count($this->colorBreaks) - 1))];
            $endColor = $this->colorBreaks[ceil($distFromMin * (count($this->colorBreaks) - 1))];
    
            $distFromMin *= count($this->colorBreaks) - 1;
            while ($distFromMin > 1) {
                $distFromMin--;
            }
        } else {
            die("Please pass more than one color or null to use default red-green colors.");
        }
    
        // Remove hex from string
        if ($startColor[0] === '#') {
            $startColor = substr($startColor, 1);
        }
        if ($endColor[0] === '#') {
            $endColor = substr($endColor, 1);
        }
    
        // Parse hex
        list($ra, $ga, $ba) = sscanf("#$startColor", "#%02x%02x%02x");
        list($rz, $gz, $bz) = sscanf("#$endColor", "#%02x%02x%02x");
    
        // Get rgb based on
        $distFromMin = $distFromMin;
        $distDiff = 1 - $distFromMin;
        $r = intval(($rz * $distFromMin) + ($ra * $distDiff));
        $r = min(max(0, $r), 255);
        $g = intval(($gz * $distFromMin) + ($ga * $distDiff));
        $g = min(max(0, $g), 255);
        $b = intval(($bz * $distFromMin) + ($ba * $distDiff));
        $b = min(max(0, $b), 255);
        
        return \Libby\Color\Color::fromRGB($r, $g, $b);
    
        
    }
    
}