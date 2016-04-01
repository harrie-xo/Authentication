<?php 
namespace Signature\Authentication;

/*Token
  only set up property and they will cover all key and secret/token.
  they will use to Authentication & Request class.
*/

class Token{
	
	private $key;
	private $token;

	public function __construct($key,  $token){
		$this->key = $key;
		$this->codeSignature = $token;
	}

	public function getKey(){
		return $this->key;
	}

	public function getToken(){
		return $this->token;
	}
}