<?php
/**
 * @author Jan Habbo Brüning <jan.habbo.bruening@gmail.com> 
 * @date 2018-03-17
 */

namespace Libby;

class Validate {
	
	/**
	 * Validate email
	 */
	public static function email ( $email, array $params = null ) {
		
		if (!preg_match('#^(.*?)\@(.*?)\.([a-z0-9]{2,})$#i', $email)) {
			throw new Exception\Input('ExceptionValidateEmail');
		}
		
		if (empty($params['skipDns']) AND self::isOnline()) {
		
			list($mbox, $server)	=	explode('@', $email);
		
			if (!checkdnsrr($server, 'MX') AND !checkdnsrr($server, 'A')) {
				throw new Exception\Input('ExceptionValidateEmailDns');
			}
		}
	}
	
	
	/**
	 * Validate if php is connected to the internet 
	 */
	public static function isOnline ( ) {
		
		return @fsockopen("www.example.com", 80);		
	}
	
	
	/**
	 * Validate maximum length
	 */
	public static function maxLength ( $string, $maxLength ) {
		
		if (strlen($string) > $maxLength) {
			throw new ExceptionInput('ExceptionValidateMaxLength', $maxLength);
		}
	}
	
	
	/**
	 * Validate any value
	 */
	public static function notEmpty ( $string ) {
	    	
	    if (empty($string)) {
	        throw new Exception\Input('ExceptionValidateEmpty');
	    }
	}
	
	
	/**
	 * Validate post input
	 */
	public static function post ( $data, array $params = null ) {
		
		if (!is_array($data)) {
			
			$data	=	[
				$data	
			];
		}
						
		foreach ($data as $index => $key) {
				
		    if (is_array($key)) {
		        
		        foreach ($key as $fieldKey) {
		            
		            if (empty($_POST[$index][$fieldKey])) {
		                throw new \Libby\Exception\Input('MissingPostData');
		            }
		        }		        
		    }
		    elseif (preg_match('#^([a-z0-9]{1,})\.([a-z0-9]{1,})$#i', $key, $match)) {
		        
		        if (empty($_POST[$match[1]][$match[2]])) {
		            throw new \Libby\Exception\Input('MissingPostData');
		        }
		    }
		    elseif (empty($_POST[$key])) {
				throw new \Libby\Exception\Input('MissingPostData');
			}
		}
		
		return true;
	}
	
	
	/**
	 * Validate post input
	 */
	public static function postSingle ( $data, array $params = null ) {
	
	    if (!is_array($data)) {
	
	        $data	=	[
	            $data
	        ];
	    }
		
	    foreach ($data as $key) {
	
	        if (!empty($_POST[$key])) {
	            return true;
	        }
	    }
	
	    throw new \Libby\Exception\Input('MissingPostData');
	}
}
?>