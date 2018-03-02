<?php 
/**
 * 
 */

namespace Libby;

class File {
	
	protected $filepath;
	
	
	/**
	 * 
	 */
	public function __construct ( $filepath ) {
		
		$this->filepath	=	$filepath;		
	}
	
	
	//
	// Base methods
	//
	/**
	 * 
	 */
	public function isDir ( ) {
		
		return is_dir($this->filepath);
	}
}
?>