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
			throw new Exception\Input('ExceptionValidateEmail', $email);
		}
		
		if (empty($params['skipDns']) AND self::isOnline()) {
		
			list($mbox, $server)	=	explode('@', $email);
		
			if (!checkdnsrr($server, 'MX') AND !checkdnsrr($server, 'A')) {
				throw new Exception\Input('ExceptionValidateEmailDns', $email);
			}
		}
	}
	
	
	/**
	 * Validate if php is connected to the internet 
	 */
	public static function isOnline ( ) {
		
		return @fsockopen("www.example.com", 80);		
	}
}
?>