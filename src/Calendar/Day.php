<?php 
/**
 * 
 */

namespace Libby\Calendar;

class Day {
	
	protected $date;
	protected $monthType;
	
	public $data;
	
	/**
	 * 
	 */
	public function __construct ( array $params = null ) {
		
		if (empty($params['date'])) {
			$params['date'] = date('Y-m-d');
		}
		
		$this->date = \Libby\Date::format($params['date'], 'Y-m-d');
		
		$this->monthType = empty($params['monthType']) ? 'current' : $params['monthType'];
	}
	
	
	//
	// Base methods
	//
	/**
	 * 
	 */
	public function getDate ( ) {
		
		return $this->date;
	}
	
	
	/**
	 * 
	 */
	public function getMonthType ( ) {
		
		return $this->monthType;
	}
	
	
	/**
	 * 
	 */
	public function setData ( $data ) {
		
		$this->data = $data;
	}
	
	
	/**
	 * 
	 */
	public function toString ( $format = 'Y-m-d' ) {
		
		return \Libby\Date::format($this->date, $format);
	}
}