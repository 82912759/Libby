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
    public function get ( array $params ) {
        
        $ch = curl_init($this->uri);
        
        if (!empty($params['username'])) {
            curl_setopt($ch, CURLOPT_USERPWD, $params['username'] . ':' . $params['password']);
            curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
        }
        
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_TIMEOUT, 60);
        curl_setopt($ch, CURLOPT_HEADER, true);
        
        $result = curl_exec($ch);

        $header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($result, 0, $header_size);
		$body = substr($result, $header_size);
		
		return new Response(explode("\n\r", $header), $body);
        
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
		
		if (!empty($params['username'])) {
		    curl_setopt($ch, CURLOPT_USERPWD, $params['username'] . ':' . $params['password']);
		    curl_setopt($ch, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
		}
		
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
		curl_setopt($ch, CURLOPT_TIMEOUT, 60);
		curl_setopt($ch, CURLOPT_HEADER, true);			                                                                      
		// curl_setopt($ch, CURLOPT_HTTPHEADER,		$headers);
			
		$result = curl_exec($ch);
		
		$header_size = curl_getinfo($ch, CURLINFO_HEADER_SIZE);
		$header = substr($result, 0, $header_size);
		$body = substr($result, $header_size);
		
		return new Response(explode("\n\r", $header), $body);
	}
}
?>