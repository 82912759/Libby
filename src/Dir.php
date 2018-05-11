<?php 
/**
 * 
 */

namespace Libby;

class Dir extends Utility\IteratorBase {
	
	/**
	 * 
	 */
	public function __construct ( $path, array $params = null ) {
		
		if (!file_exists($path)) {
			throw new Exception\FileNotFound('Path does not exists: ' . $path);
		}
		
		if (substr($path, -1) != '/') {
			$path	.=	'/';
		}
		
		$this->path	=	$path;
		
		$dir		=	dir($this->path);
		
		while (false !== ($file = $dir->read())) {
				
			if (empty($params['showHidden']) AND $file{0} == '.' AND $file != '.htaccess') {
				continue;
			}
				
			if ($file == '.' OR $file == '..') {
				continue;
			}
				
			if ($file == '.git') {
				continue;
			}
				
			if (!empty($params['regex']) AND !preg_match($params['regex'], $file)) {
				continue;
			}
			
			if (is_dir($this->path . $file)) {
				$this->items[]	=	new File($this->path . $file . '/');
			}
			else {	
				$this->items[]	=	new File($this->path . $file);
			}
		}
		
		// sort($this->items);
		
		$this->itemCount	=	count($this->items);
	}
	
	
	//
	// Static methods
	//
	/**
	 * 
	 */
	public static function create ( $path ) {
	    
	    $segments = explode('/', $path);
	    $npath = (string) null;
	    
	    foreach ($segments as $xseg) {
	        
	        if (empty($xseg)) {
	            continue;
	        }
	        
	        $npath .= '/' . $xseg;
	        
	        if (!file_exists($npath)) {
	            
	            mkdir($npath);
	        }
	    }	    
	}
}
?>