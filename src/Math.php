<?php
/**
 * 
 */

namespace Libby;

class Math {
	
	//
	// Static methods
	//
	/**
	 * 
	 */
	public static function toFloat ( $input ) {
		
		if (strpos($input, ',') !== false AND strpos($input, '.') !== false) {
			$input	=	str_replace('.', '', $input);
			$input	=	str_replace(',', '.', $input);
		}
		
		if (strpos($input, ',') !== false) {
			$input	=	str_replace(',', '.', $input);
		}
		
		return (float) $input;		
	}
	
	
	/**
	 * Convert input to integer
	 */
	public static function toInt ( $input ) {
		
		return (int) $input;
	}
}
?>