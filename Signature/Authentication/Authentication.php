<?php 
namespace Signature\Authentication;

/**Authentication
   The Authentication class will cover all method that will contain class istance of Token & Request.
**/

class Authentication{

	public function token($key,  $token){
		
		return new Token($key,  $token);
	}

	public function request($method,  $path,  array $params){
		return new Request($method,  $path,  $params);
	}
}