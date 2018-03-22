<?php 
/**
 * 
 */

namespace Libby;

class Date {
		
	//
	// Static methods
	//
	/**
	 * Convert date into stamp
	 */
	public static function stamp ( $date ) {
		
		return self::format($date, 'U');
	}
	
	
	/**
	 * 
	 */
	public static function diff ( $date1, $date2 ) {
		
		$stamp1	=	self::stamp($date1);
		$stamp2	=	self::stamp($date2);
		
		return $stamp2 - $stamp1;
	}
	
	
	/**
	 * For mat din date
	 */
	public static function dinformat ( $date, $format ) {
		
		$da		=	explode(' ', $date);
		$date	=	$da[0];
		$time	=	!empty($da[1]) ? $da[1] : '00:00:00'; 

		$da		=	explode('.', $date);
			
		if ((int)$da[2] < 100) {
			$da[2]	=	'20' . $da[2];
		}
		
		$date	=	$da[2] . '-' . $da[1] . '-' . $da[0] . ' ' . $time;
				
		return date($format, Date::iso2stamp($date));		
	}
	
	
	/**
	 * Format date
	 */
	public static function format ( $date, $format ) {
		
		if (preg_match('#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{2}:[0-9]{2}:[0-9]{2})$#i', $date)) {
			return self::isoformat($date, $format);
		}
		elseif (preg_match('#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2})$#i', $date)) {			
			return self::isoformat($date . ':00', $format);
		}
		elseif (preg_match('#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2})(.*?)$#i', $date)) {			
			return self::isoformat($date, $format);
		}
		elseif (preg_match('#^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4}) ([0-9]{1,2})$#i', $date)) {
			return self::dinformat($date . ':00', $format);
		}
		elseif (preg_match('#^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{4})(.*?)$#i', $date)) {
			return self::dinformat($date, $format);
		}
		elseif (preg_match('#^([0-9]{1,2})\.([0-9]{1,2})\.([0-9]{2})$#i', $date)) {
			return self::dinformat($date, $format);
		}
		else {
			throw new Exception\Input('Except_Date_Format');
		}
	}
	
	
	/**
	 * Convert iso date to unix timestamp
	 */
	public static function iso2stamp ( $date ) {
		
		if (preg_match('#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2}):([0-9]{1,2})$#', $date, $match)) {
			
			list($date, $time)				=	explode(' ', $date);
			list($year, $month, $day)		=	explode('-', $date);
			list($hour, $minute, $second)	=	explode(':', $time);
		}
		elseif (preg_match('#^([0-9]{4})-([0-9]{1,2})-([0-9]{1,2}) ([0-9]{1,2}):([0-9]{1,2})$#', $date, $match)) {
			
			list($string, $year, $month, $day, $hour, $minute)	=	$match;
			$second							=	'00';
		}
		else {
			
			list($year, $month, $day)		=	explode('-', $date);
			$hour	=	0;
			$minute	=	0;
			$second	=	0;
		}
				
		return mktime($hour, $minute, $second, $month, $day, $year);
	}
	
	
	/**
	 * For mat isodate
	 */
	public static function isoformat ( $date, $format ) {
		
		return date($format, Date::iso2stamp($date));		
	}
}
?>