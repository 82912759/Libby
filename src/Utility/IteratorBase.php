<?php 
/**
 * 
 */

namespace Libby\Utility;

abstract class IteratorBase implements \Iterator {
	
	protected $index;
	protected $items;
	protected $itemCount = 0;
	
	
	//
	// Iterator methods
	//
	/**
	 * 
	 */
	public function current ( ) {
		
		return $this->items[$this->index];
	}
	
	
	/**
	 * 
	 */
	public function next ( ) {
		
		++$this->index;
	}
	
	
	/**
	 * 
	 */
	public function key ( ) {
		
		return $this->index;
	}
	
	
	/**
	 * 
	 */
	public function valid ( ) {
		
		return isset($this->items[$this->index]);
	}
	
	
	/**
	 * 
	 */
	public function rewind ( ) {
		
		$this->index = 0;
	}
}
?>