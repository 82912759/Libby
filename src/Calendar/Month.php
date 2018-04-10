<?php 
/**
 * 
 */

namespace Libby\Calendar;

class Month {
	
	protected $date;
	protected $days = [ ];
	
	
	/**
	 * @param params.date
	 */
	public function __construct ( array $params = null ) {
		
		if (empty($params['date'])) {			
			$params['date'] = date('Y-m-d');
		}
		
		$this->setDate($params['date']);
		
		$month = \Libby\Date::format($this->date, 'm');
		$year = \Libby\Date::format($this->date, 'Y');
		
		
		if (!empty($params['fullWeeks'])) {			
			
			$preMonth = ($month > 1) ? ($month - 1) : 12;
			$preYear = ($month > 1) ? $year : ($year - 1);
						
			$preDays = \Libby\Date::format($preYear . '-' . $preMonth . '-01', 't');
			$offset = \Libby\Date::format($year . '-' . $month . '-01', 'N');
			$start = $preDays - ($offset - 2);
			
			for ($i = $start; $i <= $preDays; ++$i) {		
				$this->days[] = new Day([
					'date' => $preYear . '-' . $preMonth . '-' . $i,
					'monthType' => 'pre'
				]);
			}
		}
		
		$days = \Libby\Date::format($this->date, 't');
		$mdpart = \Libby\Date::format($this->date, 'Y-m');;
		
		for ($i = 1; $i <= $days; ++$i) {		
			$this->days[] = new Day([
				'date' => $mdpart . '-' . $i
			]);
		}
		
		
		if (!empty($params['fullWeeks']) and (count($this->days) % 7 != 0)) {
			
			$diff = 7 - (count($this->days) % 7);
			
			$nextMonth = ($month < 12) ? ($month + 1) : 1;
			$nextYear = ($month < 12) ? $year : ($year + 1);
						
			for ($i = 1; $i <= $diff; ++$i) {		
				$this->days[] = new Day([
					'date' => $nextYear . '-' . $nextMonth . '-' . $i,
					'monthType' => 'next'
				]);
			}
		}
	}
	
	
	//
	// Base methods
	//
	/**
	 *
	 */
	public function getDateLast ( ) {
	
		$month = \Libby\Date::format($this->date, 'm');
		$year = \Libby\Date::format($this->date, 'Y');
			
		$preMonth = ($month > 1) ? ($month - 1) : 12;
		$preYear = ($month > 1) ? $year : ($year - 1);
	
		return $preYear . '-' . $preMonth . '-01';
	}
	
	
	/**
	 *
	 */
	public function getDateNext ( ) {
	
		$month = \Libby\Date::format($this->date, 'm');
		$year = \Libby\Date::format($this->date, 'Y');
			
		$nextMonth = ($month < 12) ? ($month + 1) : 1;
		$nextYear = ($month < 12) ? $year : ($year + 1);
	
		return $nextYear . '-' . $nextMonth . '-01';
	}
	
	
	/**
	 * 
	 */
	public function getDays ( array $params = null ) {
				
		return $this->days;
	}
	
	
	/**
	 * 
	 */
	public function getDay ( $date ) {
		
		foreach ($this->days as $day) {
			
			if ($day->getDate() == \Libby\Date::format($date, 'Y-m-d')) {
				return $day;
			}
		}
		
		return null;
	}
	
	
	/**
	 * 
	 */
	public function getDayFirst ( ) {
		
		return $this->days[0];
	}
	
	
	/**
	 * 
	 */
	public function getDayLast ( ) {
		
		return $this->days[count($this->days)-1];
	}
	
	/**
	 * Set date
	 */
	public function setDate ( $date ) {
		
		$this->date = \Libby\Date::format($date, 'Y-m-d');
		
	//		$firstDay = \Libby\Date::format($date, 'Y-m') . '-01';
	}
	
	
	/**
	 * 
	 */
	public function toString ( $format = 'Y-m-d' ) {
		
		return \Libby\Date::format($this->date, $format);
	}
}
?>