<?php 
/**
 * 
 */

namespace Libby\Http;

class Request {
	
	protected $uri;
	
	
	/**
	 * 
	 */
	public function __construct ( $uri = null ) {
		
		if ($uri !== null) {
			$this->uri = $uri;
		}
	}
	
	
	/**
	 * 
	 */
	public function post ( $data = null, array $params = null ) {
		
		$ch = curl_init($this->uri);
				
		curl_setopt($ch, CURLOPT_CUSTOMREQUEST, 'POST');
		
		if (!empty($data)) {
			curl_setopt($ch, CURLOPT_POSTFIELDS, http_build_query($data));
		}		
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HEADER, true);			                                                                      
		// curl_setopt($ch, CURLOPT_HTTPHEADER,		$headers);
			
		$result = curl_exec($ch);
			
		return $result;
	}
}
?>